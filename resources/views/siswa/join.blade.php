<x-layouts.siswa title="Masuk Kelas">
    <div class="animate-fade-up text-center">
        <h1 class="font-display text-4xl leading-tight">Halo! <em class="text-brand-600">Siap belajar?</em></h1>
        <p class="mt-2 text-ink-soft">Masukkan kode kelas dari gurumu dan nama panggilanmu.</p>
    </div>

    <x-ui.card class="mt-6 animate-fade-up" padding="lg">
        <form method="POST" action="{{ route('siswa.join.store') }}" class="space-y-5">
            @csrf
            <x-ui.field label="Kode kelas" for="code" hint="6 huruf/angka, contoh: RATA5A" required>
                <x-ui.input name="code" :value="$code" placeholder="RATA5A" autocomplete="off" autocapitalize="characters"
                    class="text-center text-xl font-semibold tracking-[0.3em] uppercase" maxlength="12" required autofocus />
            </x-ui.field>

            <x-ui.field label="Nama panggilan" for="student_name" hint="Cukup nama panggilan, tidak perlu nama lengkap." required>
                <x-ui.input name="student_name" placeholder="Contoh: Dimas" maxlength="40" autocomplete="given-name" required />
            </x-ui.field>

            <x-ui.button type="submit" size="lg" class="w-full" icon-right="arrow-right">Mulai</x-ui.button>
        </form>
    </x-ui.card>

    <p class="mt-6 flex items-center justify-center gap-2 text-center text-sm text-muted">
        <x-heroicon-o-face-smile class="size-5" aria-hidden="true" />
        Ini bukan ujian. Jawab sebisamu, supaya gurumu tahu cara terbaik membantumu.
    </p>
</x-layouts.siswa>
