@props([
    'label',
    'value',
    'hint' => null,
    'icon' => null,
    'featured' => false, // satu kartu unggulan per baris (ref: Donezo)
])

<div {{ $attributes->class([
    'relative overflow-hidden rounded-2xl p-5',
    'bg-gradient-to-br from-brand-600 to-brand-800 text-white shadow-raised' => $featured,
    'card' => ! $featured,
]) }}>
    <div class="flex items-start justify-between gap-3">
        <p @class(['text-sm font-medium', 'text-white/85' => $featured, 'text-muted' => ! $featured])>{{ $label }}</p>
        @if ($icon)
            <span @class([
                'flex size-9 items-center justify-center rounded-full',
                'bg-white/15 text-white' => $featured,
                'bg-brand-50 text-brand-700' => ! $featured,
            ])>
                <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-5" aria-hidden="true" />
            </span>
        @endif
    </div>

    <p class="mt-3 text-4xl font-semibold tracking-tight tabular-nums">{{ $value }}</p>

    @if ($hint || ! $slot->isEmpty())
        <div @class(['mt-2 text-xs', 'text-white/80' => $featured, 'text-muted' => ! $featured])>
            {{ $hint }}{{ $slot }}
        </div>
    @endif
</div>
