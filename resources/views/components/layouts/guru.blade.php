@props(['title' => null])

@php
    $menu = [
        ['label' => 'Beranda', 'icon' => 'home', 'url' => url('/guru'), 'active' => request()->is('guru')],
        ['label' => 'Buat Asesmen', 'icon' => 'sparkles', 'url' => url('/guru/asesmen/buat'), 'active' => request()->is('guru/asesmen/buat')],
    ];
@endphp

<x-layouts.base :title="$title">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 btn-primary btn-md">Lewati ke konten</a>

    <div class="flex min-h-screen" x-data="{ open: false }" @keydown.escape.window="open = false">
        {{-- Sidebar --}}
        <aside
            class="no-print fixed inset-y-0 left-0 z-30 flex w-64 -translate-x-full flex-col border-r border-line bg-surface px-4 py-5 transition duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
            :class="open && 'translate-x-0'"
            aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="mb-8 flex items-center gap-2 px-2">
                <x-logo class="size-8" />
                <span class="text-lg font-semibold tracking-tight">RATA</span>
            </a>

            <p class="eyebrow mb-2 px-3">Menu</p>
            <nav class="space-y-1 text-sm">
                @foreach ($menu as $item)
                    <a href="{{ $item['url'] }}"
                        @if ($item['active']) aria-current="page" @endif
                        @class([
                            'relative flex min-h-10 items-center gap-3 rounded-xl px-3 font-medium transition',
                            'bg-brand-50 text-brand-700 before:absolute before:top-2 before:bottom-2 before:-left-4 before:w-1 before:rounded-r-full before:bg-brand-600' => $item['active'],
                            'text-muted hover:bg-subtle hover:text-ink' => ! $item['active'],
                        ])>
                        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="size-5" aria-hidden="true" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto overflow-hidden rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 p-4 text-white">
                <x-heroicon-o-academic-cap class="size-6 text-white/90" aria-hidden="true" />
                <p class="mt-2 text-sm font-semibold">Teaching at the Right Level</p>
                <p class="mt-1 text-xs text-white/80">Kelompokkan siswa sesuai kemampuan, bukan sesuai kelas.</p>
            </div>
        </aside>

        <div class="fixed inset-0 z-20 bg-ink/30 backdrop-blur-sm lg:hidden" x-show="open" x-transition.opacity x-cloak @click="open = false"></div>

        {{-- Konten --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="no-print sticky top-0 z-10 flex h-16 items-center gap-3 border-b border-line bg-surface/80 px-4 backdrop-blur lg:hidden">
                <button type="button" class="btn-ghost size-10" @click="open = true" aria-label="Buka menu">
                    <x-heroicon-o-bars-3 class="size-6" aria-hidden="true" />
                </button>
                <span class="font-semibold">{{ $title ?? 'RATA' }}</span>
            </header>

            <main id="konten" class="mx-auto w-full max-w-7xl flex-1 p-4 sm:p-6 lg:p-8">
                <x-ui.flash />
                {{ $slot }}
            </main>
        </div>
    </div>
</x-layouts.base>
