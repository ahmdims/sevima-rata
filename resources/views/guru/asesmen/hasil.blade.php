@php
    $counts = $report->levelCounts();
    $top = $report->topMisconceptions();
    $finished = $report->finished->count();
    $materials = $assessment->materials->keyBy('level');
@endphp

<x-layouts.guru :title="'Hasil '.$assessment->topic">
    <x-ui.page-header :eyebrow="$assessment->classroom->name.' · '.$assessment->grade" :title="'Hasil Asesmen '.$assessment->topic"
        description="Siswa dikelompokkan otomatis sesuai level kemampuan (Teaching at the Right Level).">
        <x-slot:actions>
            <x-ui.badge tone="brand" class="font-mono text-sm tracking-widest">Kode {{ $assessment->classroom->code }}</x-ui.badge>
            <x-ui.button variant="secondary" icon="arrow-path" :href="request()->url()">Perbarui</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if ($finished === 0)
        <x-ui.empty-state icon="user-group" title="Belum ada siswa yang selesai"
            description="Bagikan kode kelas {{ $assessment->classroom->code }} ke siswa. Hasil muncul di sini begitu mereka menekan “Kirim jawaban”." />
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.stat-card featured label="Siswa selesai" :value="$finished" icon="user-group"
                :hint="$report->pending ? $report->pending.' siswa masih mengerjakan' : 'Semua siswa sudah selesai'" />
            <x-ui.stat-card label="Perlu pendampingan" :value="$report->needSupport()" icon="hand-raised" hint="Level Pra-Dasar & Dasar" />
            <x-ui.stat-card label="Siap pengayaan" :value="$report->readyForEnrichment()" icon="rocket-launch" hint="Level Mahir" />
            <x-ui.stat-card label="Miskonsepsi terdeteksi" :value="count($report->topMisconceptions(100))" icon="light-bulb"
                :hint="$top ? 'Terbanyak: '.Str::limit(array_key_first($top), 38) : 'Tidak ada'" />
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">
            <x-ui.card title="Distribusi level" description="Jumlah siswa per kelompok" class="lg:col-span-2">
                <x-ui.level-distribution :counts="$counts" :pending="$report->pending" />
            </x-ui.card>

            <x-ui.card title="Miskonsepsi terbanyak" description="Jumlah siswa yang mengalaminya">
                @if ($top)
                    <ol class="space-y-4">
                        @foreach ($top as $label => $students)
                            <li>
                                <div class="flex items-start justify-between gap-3 text-sm">
                                    <span class="text-ink">{{ $label }}</span>
                                    <span class="shrink-0 font-semibold tabular-nums text-ink">{{ $students }}</span>
                                </div>
                                <x-ui.progress :value="$students" :max="$finished" tone="ai" class="mt-1.5" />
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-sm text-muted">Tidak ada miskonsepsi yang terdeteksi. 🎉</p>
                @endif
            </x-ui.card>
        </div>

        <h2 class="mt-10 mb-4 text-xl font-semibold">Kelompok belajar</h2>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($report->groups() as $level => $students)
                <x-ui.card class="flex flex-col">
                    <div class="flex items-center justify-between gap-2">
                        <x-ui.level-badge :level="$level" />
                        <span class="text-sm text-muted">{{ $students->count() }} siswa</span>
                    </div>
                    <p class="mt-3 text-sm text-ink-soft">
                        @if ($level < config('rata.assessment.levels'))
                            Fokus berikutnya: <strong class="text-ink">{{ $assessment->levelLabel($level + 1) }}</strong>
                        @else
                            Menguasai semua level. Siap <strong class="text-ink">pengayaan</strong>.
                        @endif
                    </p>

                    <ul class="mt-4 space-y-2">
                        @foreach ($students as $attempt)
                            <li class="flex items-center gap-3">
                                <x-ui.avatar :name="$attempt->student_name" size="sm" />
                                <span class="text-sm font-medium text-ink">{{ $attempt->student_name }}</span>
                                @if ($attempt->topMisconception())
                                    <span class="ml-auto truncate text-xs text-muted" title="{{ $attempt->topMisconception() }}">{{ Str::limit($attempt->topMisconception(), 28) }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto pt-5">
                        @if ($materials->has($level) && Route::has('guru.materi.show'))
                            <x-ui.button variant="secondary" icon="document-text" class="w-full" :href="route('guru.materi.show', [$assessment, $level])">Lihat materi</x-ui.button>
                        @elseif (Route::has('guru.materi.store'))
                            <form method="POST" action="{{ route('guru.materi.store', [$assessment, $level]) }}"
                                x-data="{ busy: false }" @submit="busy = true">
                                @csrf
                                <x-ui.button type="submit" variant="ai" icon="sparkles" class="w-full" x-bind:disabled="busy">
                                    <span x-show="!busy">Buat materi kelompok</span>
                                    <span x-show="busy" x-cloak>AI menyusun materi…</span>
                                </x-ui.button>
                            </form>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    @endif
</x-layouts.guru>
