@props(['title' => null])

{{-- Layout siswa: mobile-first, satu kolom, satu fokus per layar, ringan untuk HP dan kuota terbatas. --}}
<x-layouts.base :title="$title" body-class="bg-aurora">
    <div class="mx-auto flex min-h-screen w-full max-w-md flex-col px-4 py-6">
        <a href="{{ url('/') }}" class="mb-6 flex items-center gap-2 self-center">
            <x-logo class="size-7" />
            <span class="font-semibold tracking-tight">RATA</span>
        </a>

        <main id="konten" class="flex-1">
            <x-ui.flash />
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
