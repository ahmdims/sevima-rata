@props([
    'name',
    'value',
    'key' => null,      // huruf opsi: A, B, C, D
    'checked' => false,
])

{{--
    Pilihan jawaban siswa: kartu besar (min 56px, Fitts's law), huruf opsi jelas,
    status terpilih ditandai border + latar + ikon, bukan warna saja.
--}}
<label {{ $attributes->class('group flex min-h-14 cursor-pointer items-center gap-3 rounded-2xl border-2 border-line bg-surface px-4 py-3 transition duration-150 hover:border-brand-300 has-checked:border-brand-600 has-checked:bg-brand-50 has-focus-visible:ring-4 has-focus-visible:ring-brand-100') }}>
    <input type="radio" name="{{ $name }}" value="{{ $value }}" class="peer sr-only" @checked($checked) required>
    @if ($key)
        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-subtle text-sm font-semibold text-ink-soft transition group-has-checked:bg-brand-600 group-has-checked:text-white">{{ $key }}</span>
    @endif
    <span class="flex-1 text-base text-ink">{{ $slot }}</span>
    <x-heroicon-s-check-circle class="size-6 shrink-0 text-brand-600 opacity-0 transition group-has-checked:opacity-100" aria-hidden="true" />
</label>
