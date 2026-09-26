<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class LayoutTest extends TestCase
{
    public function test_home_page_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('di level yang tepat', false);
    }

    public function test_teacher_layout_renders(): void
    {
        $html = Blade::render('<x-layouts.guru title="Dasbor">Isi guru</x-layouts.guru>');

        $this->assertStringContainsString('Isi guru', $html);
        $this->assertStringContainsString('Buat Asesmen', $html);
    }

    public function test_student_layout_renders(): void
    {
        $html = Blade::render('<x-layouts.siswa title="Kerjakan">Isi siswa</x-layouts.siswa>');

        $this->assertStringContainsString('Isi siswa', $html);
    }

    public function test_level_badge_shows_level_name(): void
    {
        $html = Blade::render('<x-ui.level-badge :level="3" />');

        $this->assertStringContainsString('L3 · Cakap', $html);
    }

    public function test_level_badge_uses_growth_name_for_students(): void
    {
        $html = Blade::render('<x-ui.level-badge :level="0" audience="siswa" />');

        $this->assertStringContainsString('Benih', $html);
        $this->assertStringNotContainsString('Pra-Dasar', $html);
    }

    public function test_ui_kit_page_renders_all_components(): void
    {
        $this->get('/ui-kit')
            ->assertOk()
            ->assertSee('RATA UI Kit')
            ->assertSee('Dibuat AI')
            ->assertSee('role="progressbar"', false);
    }

    public function test_field_shows_validation_error_and_marks_input_invalid(): void
    {
        $errors = (new \Illuminate\Support\ViewErrorBag)->put('default', new \Illuminate\Support\MessageBag(['topic' => 'Topik wajib diisi.']));

        View::share('errors', $errors);

        $html = Blade::render('<x-ui.field label="Topik" for="topic"><x-ui.input name="topic" /></x-ui.field>');

        $this->assertStringContainsString('Topik wajib diisi.', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
    }
}
