<x-layouts.base body-class="bg-aurora">
    <header class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <x-logo class="size-8" />
            <span class="text-lg font-semibold tracking-tight">RATA</span>
        </a>
        <a href="{{ url('/join') }}" class="btn-ghost">Saya Siswa</a>
    </header>

    <section class="mx-auto max-w-4xl px-4 pt-12 pb-20 text-center sm:pt-20">
        <span class="inline-flex items-center gap-2 rounded-full border border-line bg-surface/70 px-3 py-1 text-xs font-medium text-muted">
            <span class="size-1.5 rounded-full bg-brand-500"></span>
            Asesmen diagnostik berbasis AI · SDG 4 Quality Education
        </span>

        <h1 class="mt-6 font-display text-5xl leading-[1.05] tracking-tight sm:text-7xl">
            Setiap anak belajar<br><em class="text-brand-600">di level yang tepat.</em>
        </h1>

        <p class="mx-auto mt-6 max-w-2xl text-base text-muted sm:text-lg">
            Guru cukup menulis topik. RATA membuat soal diagnostik, memetakan miskonsepsi,
            mengelompokkan siswa sesuai kemampuannya, lalu menyiapkan materi berbeda untuk tiap kelompok.
        </p>

        <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="{{ url('/guru') }}" class="btn-dark">Masuk sebagai Guru</a>
            <a href="{{ url('/join') }}" class="btn-ghost">Kerjakan Asesmen (Siswa)</a>
        </div>
    </section>

    <section class="mx-auto grid max-w-6xl gap-4 px-4 pb-24 sm:grid-cols-3">
        @foreach ([
            ['1', 'Buat asesmen', 'AI menyusun 12 soal dalam 4 level, lengkap dengan label miskonsepsi di setiap pilihan salah.'],
            ['2', 'Siswa mengerjakan', 'Cukup kode kelas dan nama dari HP. Level dihitung otomatis dan konsisten.'],
            ['3', 'Materi per kelompok', 'Dashboard mengelompokkan siswa, lalu AI membuat materi yang tepat untuk tiap level.'],
        ] as [$step, $name, $desc])
            <div class="card p-6">
                <span class="flex size-9 items-center justify-center rounded-xl bg-brand-50 text-sm font-semibold text-brand-700">{{ $step }}</span>
                <h2 class="mt-4 font-semibold">{{ $name }}</h2>
                <p class="mt-1.5 text-sm text-muted">{{ $desc }}</p>
            </div>
        @endforeach
    </section>
</x-layouts.base>
