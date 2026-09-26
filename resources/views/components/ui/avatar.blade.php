@props([
    'name',
    'size' => 'md', // sm | md | lg
])

@php
    $initials = collect(preg_split('/\s+/', trim($name)))->filter()->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
    // Warna deterministik dari nama, memakai palet brand/level agar tetap selaras.
    $palette = [
        'bg-brand-100 text-brand-800', 'bg-ai-100 text-ai-700', 'bg-level-1-soft text-level-1-ink',
        'bg-level-2-soft text-level-2-ink', 'bg-level-3-soft text-level-3-ink', 'bg-level-4-soft text-level-4-ink',
    ];
    $color = $palette[crc32($name) % count($palette)];
    $sizes = ['sm' => 'size-7 text-[11px]', 'md' => 'size-9 text-xs', 'lg' => 'size-12 text-sm'];
@endphp

<span {{ $attributes->class('inline-flex shrink-0 items-center justify-center rounded-full font-semibold '.$color.' '.($sizes[$size] ?? $sizes['md'])) }} aria-hidden="true">{{ $initials ?: '?' }}</span>
