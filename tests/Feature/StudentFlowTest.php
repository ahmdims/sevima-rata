<?php

namespace Tests\Feature;

use App\Models\Attempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_student_can_join_answer_all_questions_and_see_result(): void
    {
        $this->post('/join', ['code' => 'rata5a', 'student_name' => 'Sinta'])->assertRedirect();
        $attempt = Attempt::where('student_name', 'Sinta')->firstOrFail();

        $this->get("/kerjakan/{$attempt->id}")->assertOk()->assertSee('Soal 1 dari 12');

        // Benar di level 1-2, salah di level 3-4 → level 2 (Tumbuh).
        foreach ($attempt->assessment->questions as $question) {
            $key = $question->level <= 2
                ? $question->answer_key
                : collect($question->options)->firstWhere('key', '!=', $question->answer_key)['key'];
            $this->post("/kerjakan/{$attempt->id}", ['question_id' => $question->id, 'answer' => $key]);
        }

        $attempt->refresh();
        $this->assertTrue($attempt->isFinished());
        $this->assertSame(2, $attempt->level);
        $this->assertNotEmpty($attempt->misconceptions);

        $this->get("/hasil/{$attempt->id}")->assertOk()
            ->assertSee('Tumbuh')
            ->assertSee('Membandingkan pecahan')
            ->assertDontSee('Pra-Dasar');
    }

    public function test_unknown_class_code_shows_friendly_error(): void
    {
        $this->from('/join')->post('/join', ['code' => 'XXXXXX', 'student_name' => 'Sinta'])
            ->assertRedirect('/join')
            ->assertSessionHasErrors('code');
    }

    public function test_attempt_cannot_be_opened_from_another_device(): void
    {
        $attempt = Attempt::first();

        $this->get("/kerjakan/{$attempt->id}")->assertForbidden();
    }
}
