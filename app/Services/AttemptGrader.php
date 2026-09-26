<?php

namespace App\Services;

use App\Models\Attempt;
use App\Models\Question;

class AttemptGrader
{
    public function __construct(private LevelScorer $scorer) {}

    public function record(Attempt $attempt, Question $question, string $key): void
    {
        $attempt->answers()->updateOrCreate(
            ['question_id' => $question->id],
            ['chosen_key' => strtoupper($key), 'is_correct' => $question->isCorrect($key)],
        );
    }

    /** Hitung level & miskonsepsi, lalu tandai attempt selesai. */
    public function finish(Attempt $attempt): Attempt
    {
        $answers = $attempt->answers()->with('question')->get();

        $attempt->update([
            'level' => $this->scorer->level($answers->map(fn ($answer) => [
                'level' => $answer->question->level,
                'correct' => $answer->is_correct,
            ])),
            'misconceptions' => $this->scorer->misconceptions($answers
                ->reject(fn ($answer) => $answer->is_correct)
                ->map(fn ($answer) => $answer->question->misconceptionFor($answer->chosen_key))),
            'finished_at' => now(),
        ]);

        return $attempt;
    }
}
