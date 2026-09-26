@php
    $assessment = $attempt->assessment;
    $level = $attempt->level;
    $maxLevel = config('rata.assessment.levels');
    $growth = config("rata.level_growth.$level");
    $strengths = collect(range(1, max(0, $level)))->map(fn ($l) => $assessment->levelLabel($l))->filter();
    $next = $level < $maxLevel ? $assessment->levelLabel($level + 1) : null;
    $headline = [
        0 => 'kamu baru mulai menanam.',
        1 => 'tunasmu sudah muncul.',
        2 => 'kamu sedang tumbuh.',
        3 => 'kamu mulai berbunga.',
        4 => 'kamu sudah berbuah!',
    ][$level];
@endphp

<x-layouts.siswa title="Hasilmu">
    {{-- Peak-end: akhir asesmen dibuat hangat. Kekuatan dulu, lalu satu langkah berikutnya. Tanpa skor. --}}
    <div class="animate-fade-up text-center">
        <x-ui.level-badge :level="$level" audience="siswa" class="px-3 py-1 text-sm" />
        <h1 class="mt-4 font-display text-4xl leading-tight">
            Hebat, {{ $attempt->student_name }}!<br><em class="text-brand-600">{{ ucfirst($headline) }}</em>
        </h1>
        <p class="mt-2 text-ink-soft">Terima kasih sudah mengerjakan asesmen {{ $assessment->topic }} dengan sungguh-sungguh.</p>
    </div>

    <div class="mt-6 space-y-4">
        @if ($attempt->feedback)
            <x-ui.card class="animate-fade-up">
                <div class="flex gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-ai-50 text-ai-600">
                        <x-heroicon-o-sparkles class="size-5" aria-hidden="true" />
                    </span>
                    <p class="text-ink-soft">{{ $attempt->feedback }}</p>
                </div>
            </x-ui.card>
        @endif

        <x-ui.card class="animate-fade-up">
            <h2 class="flex items-center gap-2 font-semibold">
                <x-heroicon-o-star class="size-5 text-level-2" aria-hidden="true" />
                Yang sudah kamu kuasai
            </h2>
            @if ($strengths->isNotEmpty())
                <ul class="mt-3 space-y-2">
                    @foreach ($strengths as $strength)
                        <li class="flex items-start gap-2 text-ink-soft">
                            <x-heroicon-m-check-circle class="mt-0.5 size-5 shrink-0 text-success-600" aria-hidden="true" />
                            {{ $strength }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-2 text-ink-soft">Kamu sudah berani mencoba semua soal. Itu langkah pertama yang paling penting!</p>
            @endif
        </x-ui.card>

        <x-ui.card class="animate-fade-up">
            <h2 class="flex items-center gap-2 font-semibold">
                <x-heroicon-o-arrow-trending-up class="size-5 text-brand-600" aria-hidden="true" />
                Langkah berikutnya
            </h2>
            <p class="mt-2 text-ink-soft">
                @if ($next)
                    Yuk, latihan <strong class="text-ink">{{ Str::lcfirst($next) }}</strong>. Gurumu akan menyiapkan latihan yang pas untukmu.
                @else
                    Kamu siap untuk tantangan yang lebih seru! Gurumu akan menyiapkan soal pengayaan.
                @endif
            </p>
        </x-ui.card>
    </div>

    <p class="mt-6 text-center text-sm text-muted">Kamu boleh menutup halaman ini sekarang. 🌱</p>
</x-layouts.siswa>
