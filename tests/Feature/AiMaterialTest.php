<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Services\DiagnosticImporter;
use App\Services\RataAi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        config(['rata.llm.fake' => true]);
    }

    public function test_material_fixtures_pass_validation(): void
    {
        foreach (DiagnosticImporter::fixture('materials-pecahan') as $level => $material) {
            $this->assertSame([], app(RataAi::class)->validateMaterial($material), "level $level");
        }
    }

    public function test_teacher_generates_and_views_group_material(): void
    {
        $assessment = Assessment::first();

        $this->post("/guru/asesmen/{$assessment->id}/materi/2")
            ->assertRedirect("/guru/asesmen/{$assessment->id}/materi/2");

        $this->assertDatabaseHas('materials', ['assessment_id' => $assessment->id, 'level' => 2]);

        $this->get("/guru/asesmen/{$assessment->id}/materi/2")->assertOk()
            ->assertSee('Menjumlah Pecahan Berpenyebut Sama')
            ->assertSee('Tips mengajar')
            ->assertSee('Dimas');

        $this->get("/guru/asesmen/{$assessment->id}/hasil")->assertSee('Lihat materi');
    }

    public function test_finished_student_gets_encouraging_feedback_without_scores(): void
    {
        $assessment = Assessment::first();
        $this->post('/join', ['code' => 'RATA5A', 'student_name' => 'Tono']);
        $attempt = $assessment->attempts()->where('student_name', 'Tono')->first();

        foreach ($assessment->questions as $question) {
            $this->post("/kerjakan/{$attempt->id}", ['question_id' => $question->id, 'answer' => $question->answer_key]);
        }

        $feedback = $attempt->fresh()->feedback;
        $this->assertStringContainsString('Tono', $feedback);
        $this->assertDoesNotMatchRegularExpression('/\d+\s*\/\s*12|salah|gagal/i', $feedback);
    }
}
