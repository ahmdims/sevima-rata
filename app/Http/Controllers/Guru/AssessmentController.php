<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Classroom;
use App\Services\AssessmentReport;
use App\Services\DiagnosticImporter;
use App\Services\RataAi;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function create(Request $request)
    {
        $classrooms = Classroom::orderBy('name')->get();

        if ($classrooms->isEmpty()) {
            return redirect()->route('guru.index')->withErrors(['name' => 'Buat kelas dulu sebelum membuat asesmen.']);
        }

        return view('guru.asesmen.create', [
            'classrooms' => $classrooms,
            'selected' => $request->query('kelas', $classrooms->first()->id),
        ]);
    }

    public function store(Request $request, RataAi $ai, DiagnosticImporter $importer)
    {
        $data = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'topic' => ['required', 'string', 'min:3', 'max:80'],
            'notes' => ['nullable', 'string', 'max:300'],
        ], [], ['classroom_id' => 'kelas', 'topic' => 'topik', 'notes' => 'catatan']);

        $classroom = Classroom::findOrFail($data['classroom_id']);
        [$payload, $source] = $ai->generateDiagnostic($data['topic'], $classroom->grade, $data['notes'] ?? null);

        $assessment = $classroom->assessments()->create([
            'topic' => $source === 'ai' ? $data['topic'] : 'Pecahan',
            'grade' => $classroom->grade,
            'notes' => $data['notes'] ?? null,
            'source' => $source,
        ]);
        $importer->import($assessment, $payload);

        return redirect()->route('guru.asesmen.show', $assessment)->with('status', $source === 'ai'
            ? 'Soal diagnostik selesai disusun AI. Tinjau dulu sebelum dipublikasikan.'
            : 'AI tidak aktif, jadi dipakai soal contoh Pecahan agar demo tetap berjalan.');
    }

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
