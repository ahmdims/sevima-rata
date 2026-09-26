<x-layouts.guru title="Beranda">
    <x-ui.page-header eyebrow="Selamat datang" title="Kelas & Asesmen"
        description="Buat asesmen diagnostik, bagikan kode kelas, lalu lihat siswa terkelompok otomatis.">
        @if (Route::has('guru.asesmen.create') && $classrooms->isNotEmpty())
            <x-slot:actions>
                <x-ui.button variant="ai" icon="sparkles" :href="route('guru.asesmen.create')">Buat Asesmen</x-ui.button>
            </x-slot:actions>
        @endif
    </x-ui.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            @forelse ($classrooms as $classroom)
                <x-ui.card :title="$classroom->name" :description="$classroom->subject.' · '.$classroom->grade">
                    <x-slot:actions>
                        <span class="text-xs text-muted">Kode kelas</span>
                        <x-ui.badge tone="brand" class="font-mono text-sm tracking-widest">{{ $classroom->code }}</x-ui.badge>
                    </x-slot:actions>

                    @forelse ($classroom->assessments as $assessment)
                        <div class="flex flex-col gap-3 border-t border-line py-4 first:border-t-0 first:pt-0 last:pb-0 sm:flex-row sm:items-center">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-medium text-ink">{{ $assessment->topic }}</p>
                                    @if ($assessment->isPublished())
                                        <x-ui.badge tone="success" icon="check">Aktif</x-ui.badge>
                                    @else
                                        <x-ui.badge>Draf</x-ui.badge>
                                    @endif
                                </div>
                                <p class="mt-0.5 text-sm text-muted">
                                    {{ $assessment->finished_count }} siswa selesai
                                    @if ($assessment->attempts_count > $assessment->finished_count)
                                        · {{ $assessment->attempts_count - $assessment->finished_count }} sedang mengerjakan
                                    @endif
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <x-ui.button variant="secondary" size="sm" :href="route('guru.asesmen.show', $assessment)">Soal</x-ui.button>
                                <x-ui.button size="sm" icon="chart-bar" :href="route('guru.asesmen.hasil', $assessment)">Lihat hasil</x-ui.button>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state icon="clipboard-document-list" title="Belum ada asesmen"
                            description="AI akan menyusun soal diagnostik 4 level dalam kurang dari satu menit.">
                            @if (Route::has('guru.asesmen.create'))
                                <x-slot:action>
                                    <x-ui.button variant="ai" icon="sparkles" :href="route('guru.asesmen.create', ['kelas' => $classroom->id])">Buat Asesmen</x-ui.button>
                                </x-slot:action>
                            @endif
                        </x-ui.empty-state>
                    @endforelse
                </x-ui.card>
            @empty
                <x-ui.empty-state icon="academic-cap" title="Belum ada kelas"
                    description="Buat kelas pertama Anda di panel samping. Siswa akan masuk memakai kode kelas." />
            @endforelse
        </div>

        <x-ui.card title="Kelas baru" description="Kode kelas dibuat otomatis." class="h-fit">
            <form method="POST" action="{{ route('guru.kelas.store') }}" class="space-y-4">
                @csrf
                <x-ui.field label="Nama kelas" for="name" required>
                    <x-ui.input name="name" placeholder="Contoh: Kelas 5B" required />
                </x-ui.field>
                <x-ui.field label="Mata pelajaran" for="subject" required>
                    <x-ui.input name="subject" placeholder="Contoh: Matematika" required />
                </x-ui.field>
                <x-ui.field label="Jenjang" for="grade" required>
                    <x-ui.select name="grade" placeholder="Pilih jenjang" required
                        :options="collect(range(1, 9))->mapWithKeys(fn ($g) => [($g <= 6 ? 'Kelas '.$g.' SD' : 'Kelas '.$g.' SMP') => ($g <= 6 ? 'Kelas '.$g.' SD' : 'Kelas '.$g.' SMP')])->all()" />
                </x-ui.field>
                <x-ui.button type="submit" variant="secondary" icon="plus" class="w-full">Buat kelas</x-ui.button>
            </form>
        </x-ui.card>
    </div>
</x-layouts.guru>
