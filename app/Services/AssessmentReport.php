<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Collection;

/** Ringkasan hasil asesmen untuk dashboard guru. */
class AssessmentReport
{
    public readonly Collection $finished;

    public readonly int $pending;

    public function __construct(public readonly Assessment $assessment)
    {
        $attempts = $assessment->attempts()->orderBy('student_name')->get();
        $this->finished = $attempts->filter->isFinished()->values();
        $this->pending = $attempts->count() - $this->finished->count();
    }

    /** @return array<int, int> [level => jumlah siswa] */
    public function levelCounts(): array
    {
        $counts = array_fill(0, config('rata.assessment.levels') + 1, 0);
        foreach ($this->finished as $attempt) {
            $counts[$attempt->level]++;
        }

        return $counts;
    }

    /** @return Collection<int, Collection> [level => attempts], hanya level yang ada siswanya */
    public function groups(): Collection
    {
        return $this->finished->groupBy('level')->sortKeys();
    }

    /** @return array<string, int> top-N miskonsepsi kelas: [label => jumlah siswa] */
    public function topMisconceptions(int $limit = 5): array
    {
        $counts = [];
        foreach ($this->finished as $attempt) {
            foreach (array_keys($attempt->misconceptions ?? []) as $label) {
                $counts[$label] = ($counts[$label] ?? 0) + 1;
            }
        }
        arsort($counts);

        return array_slice($counts, 0, $limit, true);
    }

    /** Miskonsepsi paling umum di satu kelompok level (untuk prompt materi). */
    public function groupMisconceptions(int $level, int $limit = 3): array
    {
        $counts = [];
        foreach ($this->finished->where('level', $level) as $attempt) {
            foreach ($attempt->misconceptions ?? [] as $label => $n) {
                $counts[$label] = ($counts[$label] ?? 0) + $n;
            }
        }
        arsort($counts);

        return array_slice(array_keys($counts), 0, $limit);
    }

    public function needSupport(): int
    {
        return $this->finished->whereIn('level', [0, 1])->count();
    }

    public function readyForEnrichment(): int
    {
        return $this->finished->where('level', config('rata.assessment.levels'))->count();
    }
}
