<?php

namespace App\Services;

/**
 * Penentuan level siswa — deterministik, tanpa LLM.
 *
 * Level = level tertinggi n di mana siswa benar >= pass_threshold soal
 * di SETIAP level 1..n. Jika level 1 pun belum lulus, level = 0 (Pra-Dasar).
 * Jawaban yang sama selalu menghasilkan level yang sama (bisa diaudit guru).
 */
class LevelScorer
{
    public function __construct(
        private int $levels = 4,
        private int $passThreshold = 2,
    ) {}

    public static function fromConfig(): self
    {
        return new self(config('rata.assessment.levels'), config('rata.assessment.pass_threshold'));
    }

    /**
     * @param  iterable<array{level:int, correct:bool}>  $results
     */
    public function level(iterable $results): int
    {
        $correctPerLevel = array_fill(1, $this->levels, 0);

        foreach ($results as $result) {
            if ($result['correct'] && isset($correctPerLevel[$result['level']])) {
                $correctPerLevel[$result['level']]++;
            }
        }

        $level = 0;
        foreach ($correctPerLevel as $n => $correct) {
            if ($correct < $this->passThreshold) {
                break;
            }
            $level = $n;
        }

        return $level;
    }

    /**
     * Label miskonsepsi dari jawaban salah, diurutkan dari yang paling sering.
     *
     * @param  iterable<string|null>  $labels
     * @return array<string, int>
     */
    public function misconceptions(iterable $labels): array
    {
        $counts = [];
        foreach ($labels as $label) {
            if ($label) {
                $counts[$label] = ($counts[$label] ?? 0) + 1;
            }
        }
        arsort($counts);

        return $counts;
    }
}
