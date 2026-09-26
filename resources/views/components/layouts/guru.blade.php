@props(['title' => null, 'heading' => null])

@php
    $menu = [
        ['label' => 'Beranda', 'url' => url('/guru'), 'active' => request()->is('guru')],
        ['label' => 'Buat Asesmen', 'url' => url('/guru/asesmen/buat'), 'active' => request()->is('guru/asesmen/buat')],
    ];
@endphp

<x-layouts.base :title="$title">
    <div class="flex min-h-screen" x-data="{ open: false }">
        {{-- Sidebar --}}
        <aside
            class="no-print fixed inset-y-0 left-0 z-30 w-64 -translate-x-full border-r border-line bg-surface p-5 transition lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            :class="open && 'translate-x-0'">
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-2">
                <x-logo class="size-8" />
                <span class="text-lg font-semibold tracking-tight">RATA</span>
            </a>

            <nav class="space-y-1 text-sm">
                @foreach ($menu as $item)
                    <a href="{{ $item['url'] }}"
                        @class([
                            'flex items-center rounded-xl px-3 py-2 font-medium transition',
                            'bg-brand-50 text-brand-700' => $item['active'],
                            'text-muted hover:bg-slate-50 hover:text-ink' => ! $item['active'],
                        ])>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="absolute inset-x-5 bottom-5 rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 p-4 text-white">
                <p class="text-sm font-semibold">Teaching at the Right Level</p>
                <p class="mt-1 text-xs text-white/80">Kelompokkan siswa sesuai kemampuan, bukan sesuai kelas.</p>
            </div>
        </aside>

        <div class="fixed inset-0 z-20 bg-ink/30 lg:hidden" x-show="open" x-cloak @click="open = false"></div>

        {{-- Konten --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="no-print sticky top-0 z-10 flex h-16 items-center gap-3 border-b border-line bg-surface/80 px-4 backdrop-blur lg:px-8">
                <button type="button" class="btn-ghost px-3 py-2 lg:hidden" @click="open = true" aria-label="Buka menu">☰</button>
                <h1 class="truncate text-lg font-semibold">{{ $heading ?? $title }}</h1>
                <div class="ml-auto flex items-center gap-2">{{ $actions ?? '' }}</div>
            </header>

            <main class="flex-1 p-4 lg:p-8">
                <x-flash />
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.base>
