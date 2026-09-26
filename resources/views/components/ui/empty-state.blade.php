@props([
    'icon' => 'inbox',
    'title',
    'description' => null,
])

<div {{ $attributes->class('flex flex-col items-center rounded-2xl border border-dashed border-line bg-surface/60 px-6 py-12 text-center') }}>
    <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
        <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" aria-hidden="true" />
    </span>
    <h3 class="mt-4 font-semibold text-ink">{{ $title }}</h3>
    @if ($description)
        <p class="mt-1 max-w-sm text-sm text-muted">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
