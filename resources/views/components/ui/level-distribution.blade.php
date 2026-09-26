@props([
    'counts' => [],   // [level => jumlah siswa], mis. [0 => 2, 1 => 5, 2 => 3, 3 => 1, 4 => 1]
    'pending' => 0,   // siswa yang belum mengerjakan — ditampilkan dengan arsiran
])

@php
    $bars = ['bg-level-0', 'bg-level-1', 'bg-level-2', 'bg-level-3', 'bg-level-4'];
    $peak = max(1, $pending, ...array_values($counts ?: [0]));
    $total = array_sum($counts);
@endphp

{{-- Distribusi siswa per level. Setiap bar diberi label angka + nama agar tidak bergantung pada warna. --}}
<figure {{ $attributes }}>
    <div class="flex h-48 items-end gap-2 sm:gap-3" role="img"
        aria-label="Distribusi {{ $total }} siswa per level: @foreach (range(0, 4) as $l){{ config("rata.level_names.$l") }} {{ $counts[$l] ?? 0 }}{{ $l < 4 ? ', ' : '' }}@endforeach">
        @foreach (range(0, 4) as $level)
            @php $count = $counts[$level] ?? 0; @endphp
            <div class="flex h-full flex-1 flex-col items-center justify-end gap-1.5">
                <span class="text-sm font-semibold tabular-nums text-ink">{{ $count }}</span>
                <div class="w-full rounded-xl {{ $bars[$level] }} transition-[height] duration-500"
                    style="height: {{ max(4, $count / $peak * 100) }}%"></div>
            </div>
        @endforeach
        @if ($pending > 0)
            <div class="flex h-full flex-1 flex-col items-center justify-end gap-1.5">
                <span class="text-sm font-semibold tabular-nums text-muted">{{ $pending }}</span>
                <div class="bg-hatch w-full rounded-xl border border-line" style="height: {{ max(4, $pending / $peak * 100) }}%"></div>
            </div>
        @endif
    </div>

    <figcaption class="mt-2 flex gap-2 sm:gap-3" aria-hidden="true">
        @foreach (range(0, 4) as $level)
            <span class="flex-1 text-center text-xs text-muted">
                <span class="block font-semibold text-ink-soft">L{{ $level }}</span>
                <span class="hidden sm:inline">{{ config("rata.level_names.$level") }}</span>
            </span>
        @endforeach
        @if ($pending > 0)
            <span class="flex-1 text-center text-xs text-muted"><span class="block font-semibold text-ink-soft">—</span><span class="hidden sm:inline">Belum</span></span>
        @endif
    </figcaption>
</figure>
