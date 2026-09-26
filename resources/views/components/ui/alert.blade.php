@props([
    'tone' => 'info', // info | success | warning | danger | ai
    'title' => null,
])

@php
    $tones = [
        'info' => ['bg-brand-50 border-brand-200 text-brand-900', 'information-circle', 'text-brand-600'],
        'success' => ['bg-success-50 border-success-600/20 text-success-700', 'check-circle', 'text-success-600'],
        'warning' => ['bg-warning-50 border-warning-600/20 text-warning-700', 'exclamation-triangle', 'text-warning-600'],
        'danger' => ['bg-danger-50 border-danger-600/20 text-danger-700', 'exclamation-circle', 'text-danger-600'],
        'ai' => ['bg-ai-50 border-ai-100 text-ai-700', 'sparkles', 'text-ai-600'],
    ];
    [$box, $icon, $iconColor] = $tones[$tone] ?? $tones['info'];
@endphp

<div role="{{ $tone === 'danger' ? 'alert' : 'status' }}" {{ $attributes->class('flex gap-3 rounded-xl border px-4 py-3 text-sm '.$box) }}>
    <x-dynamic-component :component="'heroicon-o-'.$icon" class="mt-0.5 size-5 shrink-0 {{ $iconColor }}" aria-hidden="true" />
    <div class="min-w-0">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div @class(['mt-0.5' => $title])>{{ $slot }}</div>
    </div>
</div>
