@props([
    'tone' => 'neutral', // neutral | brand | ai | success | warning | danger
    'icon' => null,
])

@php
    $tones = [
        'neutral' => 'bg-subtle text-ink-soft ring-line',
        'brand' => 'bg-brand-50 text-brand-700 ring-brand-200',
        'ai' => 'bg-ai-50 text-ai-700 ring-ai-100',
        'success' => 'bg-success-50 text-success-700 ring-success-600/20',
        'warning' => 'bg-warning-50 text-warning-700 ring-warning-600/20',
        'danger' => 'bg-danger-50 text-danger-700 ring-danger-600/20',
    ];
@endphp

<span {{ $attributes->class('inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '.($tones[$tone] ?? $tones['neutral'])) }}>
    @if ($icon)
        <x-dynamic-component :component="'heroicon-m-'.$icon" class="size-3.5" aria-hidden="true" />
    @endif
    {{ $slot }}
</span>
