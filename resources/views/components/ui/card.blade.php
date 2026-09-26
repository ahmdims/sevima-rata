@props([
    'padding' => 'md', // none | sm | md | lg
    'title' => null,
    'description' => null,
])

@php
    $paddings = ['none' => '', 'sm' => 'p-4', 'md' => 'p-5 sm:p-6', 'lg' => 'p-6 sm:p-8'];
@endphp

<section {{ $attributes->class(['card', $paddings[$padding] ?? $paddings['md']]) }}>
    @if ($title || isset($actions))
        <header class="mb-4 flex items-start justify-between gap-4">
            <div>
                @if ($title)
                    <h2 class="text-base font-semibold text-ink">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mt-0.5 text-sm text-muted">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    {{ $slot }}
</section>
