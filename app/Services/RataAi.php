<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Tiga kemampuan AI RATA. Setiap output divalidasi terhadap skema; jika tidak
 * valid, AI diminta memperbaiki (maks. LLM_MAX_RETRIES). Jika tetap gagal atau
 * LLM_FAKE=true, dipakai fallback deterministik agar demo tidak pernah macet.
 */
class RataAi
{
    private const SYSTEM = 'Kamu adalah guru ahli pedagogi dan perancang asesmen diagnostik Kurikulum Merdeka di Indonesia. '
        .'Tulis dalam Bahasa Indonesia yang jelas, ramah anak, dan sesuai jenjang. Jangan membuat konten kekerasan, SARA, atau tidak pantas untuk anak. '
        .'Balas HANYA dengan satu objek JSON valid sesuai skema yang diminta, tanpa teks lain dan tanpa markdown.';

    public function __construct(private LlmClient $llm) {}

    /** @return array{0: array, 1: string} [payload, source ai|fixture] */
    public function generateDiagnostic(string $topic, string $grade, ?string $notes = null): array
    {
        $levels = config('rata.assessment.levels');
        $perLevel = config('rata.assessment.questions_per_level');

        $prompt = <<<PROMPT
        Buat asesmen diagnostik untuk topik "{$topic}" jenjang {$grade}.
        Catatan guru: {$this->orDash($notes)}

        Ketentuan:
        - Tepat {$levels} level berurutan dari kompetensi paling dasar (level 1) ke paling tinggi (level {$levels}) untuk topik ini, mengikuti pendekatan Teaching at the Right Level.
        - Setiap level punya "label" singkat (maks 8 kata) yang mendeskripsikan kompetensinya, mis. "Membandingkan pecahan".
        - Setiap level tepat {$perLevel} soal pilihan ganda dengan 3 opsi berkunci "A", "B", "C".
        - Tepat satu opsi benar. Setiap opsi salah WAJIB mewakili satu miskonsepsi umum dan spesifik siswa (bukan jawaban acak), ditulis di field "misconception" sebagai kalimat singkat. Opsi benar: "misconception": null.
        - Pakai label miskonsepsi yang sama persis bila miskonsepsinya sama di soal berbeda, agar bisa dihitung.
        - Variasikan posisi kunci jawaban. Sertakan "explanation" singkat.

        Skema JSON:
        {"levels":[{"level":1,"label":"...","questions":[{"stem":"...","options":[{"key":"A","text":"...","misconception":null},{"key":"B","text":"...","misconception":"..."},{"key":"C","text":"...","misconception":"..."}],"answer_key":"A","explanation":"..."}]}]}
        PROMPT;

        $payload = $this->ask($prompt, fn (array $data) => $this->validateDiagnostic($data));

        return $payload
            ? [$payload, 'ai']
            : [DiagnosticImporter::fixture('diagnostic-pecahan'), 'fixture'];
    }

    /** @return array{0: array, 1: string} [content, source ai|fixture] */
    public function generateMaterial(Assessment $assessment, int $level, array $misconceptions, int $studentCount): array
    {
        $current = $level > 0 ? $assessment->levelLabel($level) : 'belum menguasai kompetensi dasar';
        $target = $level < config('rata.assessment.levels')
            ? $assessment->levelLabel($level + 1)
            : 'pengayaan dan penerapan tingkat lanjut';
        $miscList = $misconceptions ? '- '.implode("\n- ", $misconceptions) : '- (tidak ada yang menonjol)';

        $prompt = <<<PROMPT
        Buat materi belajar berdiferensiasi untuk satu kelompok siswa.
        Topik: {$assessment->topic} · Jenjang: {$assessment->grade} · Jumlah siswa: {$studentCount}
        Kemampuan kelompok saat ini: {$current}
        Target pembelajaran berikutnya: {$target}
        Miskonsepsi yang sering muncul di kelompok ini:
        {$miscList}

        Ketentuan:
        - "concept_md": penjelasan konsep target dengan bahasa sederhana sesuai level, langsung meluruskan miskonsepsi di atas. Markdown, maks 180 kata.
        - "examples_md": 2 contoh soal beserta langkah penyelesaian. Markdown.
        - "exercises": tepat 3 latihan bertahap (mudah → sedang → menantang) dengan "answer".
        - "teacher_tips_md": 3 poin tips mengajar kelompok ini (aktivitas konkret, alat peraga, cara mengecek pemahaman). Markdown list.

        Skema JSON:
        {"title":"...","concept_md":"...","examples_md":"...","exercises":[{"q":"...","answer":"..."}],"teacher_tips_md":"..."}
        PROMPT;

        $content = $this->ask($prompt, fn (array $data) => $this->validateMaterial($data));

        return $content
            ? [$content, 'ai']
            : [$this->fallbackMaterial($assessment, $level, $target, $misconceptions), 'fixture'];
    }

    public function generateFeedback(string $name, int $level, string $topic, ?string $strength, ?string $next, ?string $misconception): string
    {
        if ($this->llm->enabled()) {
            $prompt = <<<PROMPT
            Tulis umpan balik untuk siswa bernama {$name} setelah asesmen diagnostik {$topic}.
            Yang sudah dikuasai: {$this->orDash($strength)}. Langkah berikutnya: {$this->orDash($next)}. Miskonsepsi utama: {$this->orDash($misconception)}.
            Aturan: 2-3 kalimat, sapa dengan "kamu", mulai dari kekuatan, sebut satu hal yang perlu dilatih dengan framing "belum", akhiri dengan semangat.
            Jangan sebut skor, angka, level, atau kata "salah"/"gagal".
            Skema JSON: {"message":"..."}
            PROMPT;

            $data = $this->ask($prompt, fn (array $data) => filled($data['message'] ?? null) ? [] : ['field "message" wajib diisi']);
            if ($data) {
                return Str::limit(trim($data['message']), 500);
            }
        }

        $opening = $strength ? "Kamu sudah menguasai ".Str::lcfirst($strength)."." : 'Kamu sudah berani mencoba semua soal, dan itu langkah yang hebat.';
        $middle = $next ? ' Sekarang kita latih '.Str::lcfirst($next).' pelan-pelan, ya.' : ' Kamu siap untuk tantangan yang lebih seru!';

        return "Keren, {$name}! {$opening}{$middle} Terus semangat belajar! 🌱";
    }

    /**
     * Minta JSON ke AI, validasi, dan minta perbaikan bila perlu.
     *
     * @param  callable(array): array<string>  $validate  mengembalikan daftar error
     */
    private function ask(string $prompt, callable $validate): ?array
    {
        if (! $this->llm->enabled()) {
            return null;
        }

        $attempt = $prompt;
        for ($try = 0; $try <= config('rata.llm.max_retries'); $try++) {
            try {
                $data = $this->llm->json(self::SYSTEM, $attempt);
                $errors = $validate($data);
                if (! $errors) {
                    return $data;
                }
                $attempt = $prompt."\n\nJawaban sebelumnya TIDAK VALID karena:\n- ".implode("\n- ", array_slice($errors, 0, 8))
                    ."\nPerbaiki dan kirim ulang JSON lengkap.";
            } catch (RuntimeException $e) {
                Log::warning('RATA AI gagal', ['try' => $try, 'error' => $e->getMessage()]);
            }
        }

        return null;
    }

    /** @return array<string> daftar error; kosong = valid */
    public function validateDiagnostic(array $data): array
    {
        $errors = [];
        $levels = $data['levels'] ?? null;
        $expectedLevels = config('rata.assessment.levels');
        $perLevel = config('rata.assessment.questions_per_level');

        if (! is_array($levels) || count($levels) !== $expectedLevels) {
            return ["harus ada tepat {$expectedLevels} level"];
        }

        foreach (array_values($levels) as $i => $level) {
            $n = $i + 1;
            if (($level['level'] ?? null) != $n) {
                $errors[] = "level ke-{$n} harus bernilai level={$n}";
            }
            if (blank($level['label'] ?? null)) {
                $errors[] = "level {$n} tidak punya label";
            }
            $questions = $level['questions'] ?? [];
            if (! is_array($questions) || count($questions) !== $perLevel) {
                $errors[] = "level {$n} harus punya tepat {$perLevel} soal";

                continue;
            }
            foreach ($questions as $q => $question) {
                $where = "level {$n} soal ".($q + 1);
                $options = $question['options'] ?? [];
                $keys = array_map(fn ($o) => strtoupper($o['key'] ?? ''), is_array($options) ? $options : []);
                if (blank($question['stem'] ?? null)) {
                    $errors[] = "{$where}: stem kosong";
                }
                if (count($keys) < 3 || count(array_unique($keys)) !== count($keys)) {
                    $errors[] = "{$where}: butuh minimal 3 opsi dengan key unik";

                    continue;
                }
                $answer = strtoupper($question['answer_key'] ?? '');
                if (! in_array($answer, $keys, true)) {
                    $errors[] = "{$where}: answer_key tidak ada di opsi";
                }
                foreach ($options as $option) {
                    if (blank($option['text'] ?? null)) {
                        $errors[] = "{$where}: ada opsi tanpa teks";
                    }
                    if (strtoupper($option['key']) !== $answer && blank($option['misconception'] ?? null)) {
                        $errors[] = "{$where}: opsi {$option['key']} salah tapi tidak punya misconception";
                    }
                }
            }
        }

        return $errors;
    }

    /** @return array<string> */
    public function validateMaterial(array $data): array
    {
        $errors = [];
        foreach (['title', 'concept_md', 'examples_md', 'teacher_tips_md'] as $field) {
            if (blank($data[$field] ?? null) || ! is_string($data[$field])) {
                $errors[] = "field \"{$field}\" wajib berupa teks";
            }
        }
        $exercises = $data['exercises'] ?? null;
        if (! is_array($exercises) || count($exercises) < 3) {
            $errors[] = 'exercises harus berisi 3 latihan';
        } else {
            foreach ($exercises as $i => $exercise) {
                if (blank($exercise['q'] ?? null) || blank($exercise['answer'] ?? null)) {
                    $errors[] = 'latihan ke-'.($i + 1).' butuh "q" dan "answer"';
                }
            }
        }

        return $errors;
    }

    private function fallbackMaterial(Assessment $assessment, int $level, string $target, array $misconceptions): array
    {
        $fixture = DiagnosticImporter::fixture('materials-pecahan');
        if (Str::contains(Str::lower($assessment->topic), 'pecahan') && isset($fixture[(string) $level])) {
            return $fixture[(string) $level];
        }

        $misc = $misconceptions ? "\n\nMiskonsepsi yang perlu diluruskan:\n- ".implode("\n- ", $misconceptions) : '';

        return [
            'title' => "Menuju: {$target}",
            'concept_md' => "Kelompok ini akan berlatih **".Str::lcfirst($target)."** pada topik {$assessment->topic}. Mulai dari contoh konkret, lalu ke gambar, baru ke simbol.{$misc}",
            'examples_md' => '1. Guru memodelkan satu soal sambil berpikir keras (*think aloud*).'."\n".'2. Siswa mengerjakan soal serupa berpasangan, lalu menjelaskan caranya.',
            'exercises' => [
                ['q' => "Soal latihan mudah tentang {$target}", 'answer' => 'Disesuaikan guru'],
                ['q' => "Soal latihan sedang tentang {$target}", 'answer' => 'Disesuaikan guru'],
                ['q' => "Soal latihan menantang tentang {$target}", 'answer' => 'Disesuaikan guru'],
            ],
            'teacher_tips_md' => "- Gunakan benda konkret atau gambar sebelum simbol.\n- Minta siswa menjelaskan jawabannya dengan kata-kata sendiri.\n- Cek pemahaman dengan 1 soal keluar (*exit ticket*).",
        ];
    }

    private function orDash(?string $value): string
    {
        return filled($value) ? $value : '-';
    }
}
