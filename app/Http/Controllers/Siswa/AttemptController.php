<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Services\AttemptGrader;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function show(Request $request, Attempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        if ($attempt->isFinished()) {
            return redirect()->route('siswa.hasil', $attempt);
        }

        $questions = $attempt->assessment->questions;
        $answered = $attempt->answers()->pluck('question_id');
        $question = $questions->first(fn ($question) => ! $answered->contains($question->id));

        return view('siswa.kerjakan', [
            'attempt' => $attempt,
            'question' => $question,
            'number' => $answered->count() + 1,
            'total' => $questions->count(),
        ]);
    }

    public function answer(Request $request, Attempt $attempt, AttemptGrader $grader)
    {
        $this->authorizeAttempt($request, $attempt);
        abort_if($attempt->isFinished(), 409);

        $data = $request->validate([
            'question_id' => ['required', 'integer'],
            'answer' => ['required', 'string', 'size:1'],
        ], ['answer.required' => 'Pilih salah satu jawaban dulu, ya.']);

        $question = $attempt->assessment->questions()->findOrFail($data['question_id']);
        abort_unless($question->hasOption($data['answer']), 422);

        $grader->record($attempt, $question, $data['answer']);

        if ($attempt->answers()->count() >= $attempt->assessment->questions()->count()) {
            $grader->finish($attempt);

            return redirect()->route('siswa.hasil', $attempt);
        }

        return redirect()->route('siswa.kerjakan', $attempt);
    }

    public function result(Request $request, Attempt $attempt)
    {
        $this->authorizeAttempt($request, $attempt);

        if (! $attempt->isFinished()) {
            return redirect()->route('siswa.kerjakan', $attempt);
        }

        return view('siswa.hasil', ['attempt' => $attempt->load('assessment')]);
    }

    /** Hanya perangkat yang memulai attempt yang boleh membukanya (tanpa akun siswa). */
    private function authorizeAttempt(Request $request, Attempt $attempt): void
    {
        abort_unless(in_array($attempt->id, $request->session()->get('attempts', []), true), 403);
    }
}
