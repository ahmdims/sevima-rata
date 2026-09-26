@props([
    'level',
    'audience' => 'guru', // guru: "L2 · Berkembang" | siswa: "Tumbuh" (metafora pertumbuhan)
])

@php
    // Kelas ditulis lengkap agar terdeteksi Tailwind saat build.
    $styles = [
        0 => 'bg-level-0-soft text-level-0-ink',
        1 => 'bg-level-1-soft text-level-1-ink',
        2 => 'bg-level-2-soft text-level-2-ink',
        3 => 'bg-level-3-soft text-level-3-ink',
        4 => 'bg-level-4-soft text-level-4-ink',
    ];
    $dots = ['bg-level-0', 'bg-level-1', 'bg-level-2', 'bg-level-3', 'bg-level-4'];
    $level = max(0, min(4, (int) $level));
    $text = $audience === 'siswa'
        ? config("rata.level_growth.$level")
        : 'L'.$level.' · '.config("rata.level_names.$level");
@endphp

{{-- Level selalu tampil sebagai teks + warna, tidak pernah warna saja (WCAG 1.4.1). --}}
<span {{ $attributes->class('inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap '.$styles[$level]) }}>
    <span class="size-1.5 rounded-full {{ $dots[$level] }}" aria-hidden="true"></span>
    {{ $text }}
</span>
