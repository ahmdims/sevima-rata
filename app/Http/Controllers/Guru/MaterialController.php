<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Services\AssessmentReport;
use App\Services\RataAi;

class MaterialController extends Controller
{
    public function store(Assessment $assessment, int $level, RataAi $ai)
    {
        abort_unless($level >= 0 && $level <= config('rata.assessment.levels'), 404);

        $report = new AssessmentReport($assessment);
        [$content, $source] = $ai->generateMaterial(
            $assessment,
            $level,
            $report->groupMisconceptions($level),
            $report->finished->where('level', $level)->count(),
        );

        // Hasil disimpan agar refresh halaman tidak memanggil AI lagi.
        $assessment->materials()->updateOrCreate(['level' => $level], ['content' => $content, 'source' => $source]);

        return redirect()->route('guru.materi.show', [$assessment, $level]);
    }

    public function show(Assessment $assessment, int $level)
    {
        $material = $assessment->materials()->where('level', $level)->firstOrFail();
        $students = (new AssessmentReport($assessment))->finished->where('level', $level);

        return view('guru.materi.show', [
            'assessment' => $assessment->load('classroom'),
            'material' => $material,
            'level' => $level,
            'students' => $students,
        ]);
    }
}
