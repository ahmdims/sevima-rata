<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_dashboard_lists_classrooms_and_assessments(): void
    {
        $this->get('/guru')->assertOk()->assertSee('Kelas 5A')->assertSee('RATA5A')->assertSee('Pecahan');
    }

    public function test_results_page_groups_students_by_level(): void
    {
        $assessment = Assessment::first();

        $this->get("/guru/asesmen/{$assessment->id}/hasil")->assertOk()
            ->assertSee('Siswa selesai')
            ->assertSee('Kelompok belajar')
            ->assertSee('L4 · Mahir')
            ->assertSee('Ayu')
            ->assertSee('Menjumlahkan pembilang dengan pembilang');
    }

    public function test_question_preview_shows_answer_key_and_misconceptions(): void
    {
        $assessment = Assessment::first();

        $this->get("/guru/asesmen/{$assessment->id}")->assertOk()
            ->assertSee('kunci jawaban')
            ->assertSee('Tertukar posisi pembilang dan penyebut');
    }

    public function test_teacher_can_create_classroom_with_generated_code(): void
    {
        $this->post('/guru/kelas', ['name' => 'Kelas 6B', 'subject' => 'IPA', 'grade' => 'Kelas 6 SD'])
            ->assertRedirect('/guru');

        $this->assertMatchesRegularExpression('/^[A-Z2-9]{6}$/', Classroom::where('name', 'Kelas 6B')->value('code'));
    }
}
