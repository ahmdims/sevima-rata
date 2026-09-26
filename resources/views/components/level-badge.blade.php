@props(['level'])

@php
    // Kelas ditulis lengkap agar terdeteksi Tailwind saat build.
    $styles = [
        0 => 'bg-red-50 text-red-700 ring-red-200',
        1 => 'bg-orange-50 text-orange-700 ring-orange-200',
        2 => 'bg-yellow-50 text-yellow-800 ring-yellow-200',
        3 => 'bg-green-50 text-green-700 ring-green-200',
        4 => 'bg-violet-50 text-violet-700 ring-violet-200',
    ];
    $level = (int) $level;
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '.($styles[$level] ?? $styles[0])]) }}>
    <span class="size-1.5 rounded-full bg-current"></span>
    L{{ $level }} · {{ config("rata.level_names.$level") }}
</span>
