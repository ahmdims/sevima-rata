@props([
    'value' => 0,
    'max' => 100,
    'label' => null,
    'showValue' => false,   // tampilkan "3/12" atau "%" di kanan label
    'segments' => null,     // angka → bar bersegmen (ref: Meridian), mis. 12 soal = 12 segmen
    'tone' => 'brand',      // brand | ai | level-0 … level-4
])

@php
    $bars = [
        'brand' => 'bg-brand-600', 'ai' => 'bg-ai-600',
        'level-0' => 'bg-level-0', 'level-1' => 'bg-level-1', 'level-2' => 'bg-level-2',
        'level-3' => 'bg-level-3', 'level-4' => 'bg-level-4',
    ];
    $bar = $bars[$tone] ?? $bars['brand'];
    $max = max(1, $max);
    $value = max(0, min($value, $max));
    $percent = round($value / $max * 100);
    $valueText = $segments ? "$value/$max" : "$percent%";
@endphp

<div {{ $attributes }}>
    @if ($label || $showValue)
        <div class="mb-1.5 flex items-center justify-between text-sm">
            <span class="font-medium text-ink">{{ $label }}</span>
            @if ($showValue)
                <span class="text-muted tabular-nums">{{ $valueText }}</span>
            @endif
        </div>
    @endif

    <div role="progressbar" aria-valuemin="0" aria-valuemax="{{ $max }}" aria-valuenow="{{ $value }}"
        @if ($label) aria-label="{{ $label }}" @endif
        @if ($segments) class="flex gap-1" @else class="h-2 overflow-hidden rounded-full bg-line" @endif>
        @if ($segments)
            @php $filled = (int) round($value / $max * $segments); @endphp
            @for ($i = 0; $i < $segments; $i++)
                <span class="h-2 flex-1 rounded-full transition-colors duration-300 {{ $i < $filled ? $bar : 'bg-line' }}"></span>
            @endfor
        @else
            <span class="block h-full rounded-full transition-[width] duration-500 {{ $bar }}" style="width: {{ $percent }}%"></span>
        @endif
    </div>
</div>
