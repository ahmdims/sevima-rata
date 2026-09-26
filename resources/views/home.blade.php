<x-layouts.base body-class="bg-aurora">
    <header class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <x-logo class="size-8" />
            <span class="text-lg font-semibold tracking-tight">RATA</span>
        </a>
        <x-ui.button variant="secondary" :href="url('/join')">Saya Siswa</x-ui.button>
    </header>

    <main>
        <section class="mx-auto max-w-4xl px-4 pt-12 pb-20 text-center sm:pt-20">
            <x-ui.badge tone="brand" class="bg-surface/70">Asesmen diagnostik berbasis AI · SDG 4 Quality Education</x-ui.badge>

            <h1 class="mt-6 font-display text-5xl leading-[1.05] tracking-tight sm:text-7xl">
                Setiap anak belajar<br><em class="text-brand-600">di level yang tepat.</em>
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-base text-ink-soft sm:text-lg">
                Guru cukup menulis topik. RATA membuat soal diagnostik, memetakan miskonsepsi,
                mengelompokkan siswa sesuai kemampuannya, lalu menyiapkan materi berbeda untuk tiap kelompok.
            </p>

            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <x-ui.button variant="dark" size="lg" :href="url('/guru')" icon-right="arrow-right">Masuk sebagai Guru</x-ui.button>
                <x-ui.button variant="secondary" size="lg" :href="url('/join')">Kerjakan Asesmen</x-ui.button>
            </div>
        </section>

        <section class="mx-auto grid max-w-6xl gap-4 px-4 pb-24 sm:grid-cols-3" aria-label="Cara kerja">
            @foreach ([
                ['sparkles', 'Buat asesmen', 'AI menyusun 12 soal dalam 4 level, lengkap dengan label miskonsepsi di setiap pilihan salah.'],
                ['device-phone-mobile', 'Siswa mengerjakan', 'Cukup kode kelas dan nama dari HP. Level dihitung otomatis dan konsisten.'],
                ['user-group', 'Materi per kelompok', 'Dashboard mengelompokkan siswa, lalu AI membuat materi yang tepat untuk tiap level.'],
            ] as [$icon, $name, $desc])
                <x-ui.card class="animate-fade-up">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-brand-50 text-brand-700">
                        <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-5" aria-hidden="true" />
                    </span>
                    <h2 class="mt-4 font-semibold">{{ $name }}</h2>
                    <p class="mt-1.5 text-sm text-muted">{{ $desc }}</p>
                </x-ui.card>
            @endforeach
        </section>
    </main>
</x-layouts.base>
