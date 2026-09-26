<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
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
        $html = Blade::render('<x-level-badge :level="3" />');

        $this->assertStringContainsString('L3 · Cakap', $html);
    }
}
