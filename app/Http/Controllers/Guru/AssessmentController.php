<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Services\AssessmentReport;

class AssessmentController extends Controller
{
    public function show(Assessment $assessment)
    {
        return view('guru.asesmen.show', [
            'assessment' => $assessment->load('classroom', 'questions'),
        ]);
    }

    public function publish(Assessment $assessment)
    {
        $assessment->update(['status' => 'published']);

        return redirect()->route('guru.asesmen.show', $assessment)
            ->with('status', "Asesmen dipublikasikan. Bagikan kode kelas {$assessment->classroom->code} ke siswa.");
    }

    public function results(Assessment $assessment)
    {
        return view('guru.asesmen.hasil', [
            'assessment' => $assessment->load('classroom', 'materials'),
            'report' => new AssessmentReport($assessment),
        ]);
    }
}
