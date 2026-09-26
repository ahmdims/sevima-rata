<?php

namespace Tests\Unit;

use App\Services\LevelScorer;
use PHPUnit\Framework\TestCase;

class LevelScorerTest extends TestCase
{
    private function results(array $correctPerLevel): array
    {
        $results = [];
        foreach ($correctPerLevel as $level => $correct) {
            for ($i = 0; $i < 3; $i++) {
                $results[] = ['level' => $level, 'correct' => $i < $correct];
            }
        }

        return $results;
    }

    public function test_all_correct_is_highest_level(): void
    {
        $this->assertSame(4, (new LevelScorer)->level($this->results([1 => 3, 2 => 3, 3 => 3, 4 => 3])));
    }

    public function test_two_of_three_passes_a_level(): void
    {
        $this->assertSame(2, (new LevelScorer)->level($this->results([1 => 2, 2 => 2, 3 => 1, 4 => 0])));
    }

    public function test_failing_level_one_is_level_zero(): void
    {
        $this->assertSame(0, (new LevelScorer)->level($this->results([1 => 1, 2 => 3, 3 => 3, 4 => 3])));
    }

    public function test_level_stops_at_first_gap_even_if_higher_levels_pass(): void
    {
        $this->assertSame(1, (new LevelScorer)->level($this->results([1 => 3, 2 => 1, 3 => 3, 4 => 3])));
    }

    public function test_misconceptions_are_counted_and_sorted(): void
    {
        $result = (new LevelScorer)->misconceptions(['A', null, 'B', 'A', 'A', 'B', 'C']);

        $this->assertSame(['A' => 3, 'B' => 2, 'C' => 1], $result);
    }
}
