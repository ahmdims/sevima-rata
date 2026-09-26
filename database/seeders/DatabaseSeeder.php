<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Classroom;
use App\Services\AttemptGrader;
use App\Services\DiagnosticImporter;
use Illuminate\Database\Seeder;

/**
 * Data demo: Kelas 5A, asesmen Pecahan (dipublikasikan), 12 siswa selesai
 * dengan pola jawaban bervariasi + 3 siswa belum selesai. Dashboard langsung
 * terisi setelah `php artisan migrate:fresh --seed`.
 */
class DatabaseSeeder extends Seeder
{
    /** [nama, level target, jumlah "kepleset" di level yang dikuasai] */
    private const STUDENTS = [
        ['Ayu', 4, 0], ['Bima', 4, 1], ['Citra', 3, 0], ['Dimas', 2, 1],
        ['Eka', 2, 0], ['Fajar', 3, 1], ['Gita', 1, 0], ['Hana', 2, 1],
        ['Indra', 1, 1], ['Joko', 0, 0], ['Kirana', 3, 0], ['Lutfi', 1, 0],
    ];

    private const PENDING = ['Maya', 'Naufal', 'Putri'];

    public function run(DiagnosticImporter $importer, AttemptGrader $grader): void
    {
        $classroom = Classroom::create([
            'name' => 'Kelas 5A',
            'subject' => 'Matematika',
            'grade' => 'Kelas 5 SD',
            'code' => 'RATA5A',
        ]);

        $assessment = Assessment::create([
            'classroom_id' => $classroom->id,
            'topic' => 'Pecahan',
            'grade' => 'Kelas 5 SD',
            'status' => 'published',
            'source' => 'fixture',
        ]);

        $importer->import($assessment, DiagnosticImporter::fixture('diagnostic-pecahan'));
        $questions = $assessment->questions()->get();

        foreach (self::STUDENTS as $index => [$name, $target, $slips]) {
            $attempt = $assessment->attempts()->create(['student_name' => $name]);

            foreach ($questions->groupBy('level') as $level => $levelQuestions) {
                foreach ($levelQuestions->values() as $i => $question) {
                    // Level yang dikuasai: benar semua kecuali $slips soal terakhir.
                    // Level di atasnya: salah minimal 2 soal, memilih distraktor.
                    $mastered = $level <= $target;
                    $correct = $mastered ? $i < 3 - $slips : $i === 2 && ($index % 2 === 0);

                    $key = $correct ? $question->answer_key : $this->distractor($question->options, $question->answer_key, $index + $i);
                    $grader->record($attempt, $question, $key);
                }
            }

            $grader->finish($attempt);
        }

        foreach (self::PENDING as $name) {
            $attempt = $assessment->attempts()->create(['student_name' => $name]);
            $grader->record($attempt, $questions[0], $questions[0]->answer_key);
        }
    }

    private function distractor(array $options, string $answerKey, int $seed): string
    {
        $wrong = array_values(array_filter($options, fn ($option) => $option['key'] !== $answerKey));

        return $wrong[$seed % count($wrong)]['key'];
    }
}
