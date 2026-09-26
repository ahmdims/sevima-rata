<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Classroom;
use App\Services\DiagnosticImporter;
use App\Services\RataAi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiDiagnosticTest extends TestCase
{
    use RefreshDatabase;

    public function test_fixture_passes_diagnostic_validation(): void
    {
        $this->assertSame([], app(RataAi::class)->validateDiagnostic(DiagnosticImporter::fixture('diagnostic-pecahan')));
    }

    public function test_validation_rejects_wrong_option_without_misconception(): void
    {
        $payload = DiagnosticImporter::fixture('diagnostic-pecahan');
        $payload['levels'][0]['questions'][0]['options'][1]['misconception'] = null;
        $payload['levels'][1]['questions'] = [];

        $errors = app(RataAi::class)->validateDiagnostic($payload);

        $this->assertNotEmpty(preg_grep('/tidak punya misconception/', $errors));
        $this->assertNotEmpty(preg_grep('/level 2 harus punya tepat 3 soal/', $errors));
    }

    public function test_fake_mode_creates_assessment_from_fixture(): void
    {
        config(['rata.llm.fake' => true]);
        $classroom = Classroom::create(['name' => 'Kelas 5B', 'subject' => 'Matematika', 'grade' => 'Kelas 5 SD']);

        $this->get('/guru/asesmen/buat')->assertOk()->assertSee('Buat dengan AI');

        $response = $this->post('/guru/asesmen', ['classroom_id' => $classroom->id, 'topic' => 'Pecahan']);

        $assessment = Assessment::firstOrFail();
        $response->assertRedirect("/guru/asesmen/{$assessment->id}");
        $this->assertSame('fixture', $assessment->source);
        $this->assertSame('draft', $assessment->status);
        $this->assertSame(12, $assessment->questions()->count());
        $this->assertSame('Membandingkan pecahan', $assessment->levelLabel(2));
    }
}
