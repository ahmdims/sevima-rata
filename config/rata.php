<?php

return [

    /*
    |--------------------------------------------------------------------------
    | LLM (Claude API)
    |--------------------------------------------------------------------------
    |
    | Semua pemanggilan AI lewat App\Services\LlmClient. Jika `fake` bernilai
    | true, RATA memakai fixture di database/fixtures sehingga demo tetap
    | jalan tanpa API key atau koneksi internet.
    |
    */

    'llm' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'base_url' => env('LLM_BASE_URL', 'https://api.anthropic.com/v1'),
        'api_version' => env('LLM_API_VERSION', '2023-06-01'),
        'model' => env('LLM_MODEL', 'claude-haiku-4-5'),
        'max_tokens' => (int) env('LLM_MAX_TOKENS', 8000),
        'timeout' => (int) env('LLM_TIMEOUT', 60),
        'max_retries' => (int) env('LLM_MAX_RETRIES', 2),
        'fake' => (bool) env('LLM_FAKE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Asesmen diagnostik
    |--------------------------------------------------------------------------
    |
    | Aturan penentuan level (deterministik, tanpa LLM): level siswa adalah
    | level tertinggi n di mana siswa benar >= pass_threshold soal di setiap
    | level 1..n. Lihat docs/PRD.md bagian 5.
    |
    */

    'assessment' => [
        'levels' => 4,
        'questions_per_level' => 3,
        'pass_threshold' => 2,
        'class_code_length' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Nama level
    |--------------------------------------------------------------------------
    |
    | `level_names` untuk guru (istilah pedagogis). `level_growth` untuk siswa:
    | metafora pertumbuhan tanaman agar level rendah terasa sebagai titik
    | mulai, bukan kegagalan (growth mindset). Lihat docs/DESIGN.md §2.
    |
    */

    'level_names' => [
        0 => 'Pra-Dasar',
        1 => 'Dasar',
        2 => 'Berkembang',
        3 => 'Cakap',
        4 => 'Mahir',
    ],

    'level_growth' => [
        0 => 'Benih',
        1 => 'Tunas',
        2 => 'Tumbuh',
        3 => 'Berbunga',
        4 => 'Berbuah',
    ],

];
