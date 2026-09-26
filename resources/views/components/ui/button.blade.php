@props([
    'variant' => 'primary', // primary | secondary | ghost | dark | ai | danger
    'size' => 'md',         // sm | md | lg
    'href' => null,
    'icon' => null,         // nama heroicon outline, mis. "sparkles"
    'iconRight' => null,
    'loading' => false,
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'ghost' => 'btn-ghost',
        'dark' => 'btn-dark',
        'ai' => 'btn-ai',
        'danger' => 'btn-danger',
    ];
    $sizes = ['sm' => 'btn-sm', 'md' => 'btn-md', 'lg' => 'btn-lg'];
    $classes = ($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
    $iconClass = $size === 'lg' ? 'size-5' : 'size-4';
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    @if ($loading) aria-busy="true" @if (! $href) disabled @endif @endif
    {{ $attributes->class($classes) }}>
    @if ($loading)
        <x-heroicon-o-arrow-path :class="$iconClass.' animate-spin'" aria-hidden="true" />
    @elseif ($icon)
        <x-dynamic-component :component="'heroicon-o-'.$icon" :class="$iconClass" aria-hidden="true" />
    @endif
    {{ $slot }}
    @if ($iconRight)
        <x-dynamic-component :component="'heroicon-o-'.$iconRight" :class="$iconClass" aria-hidden="true" />
    @endif
</{{ $tag }}>
