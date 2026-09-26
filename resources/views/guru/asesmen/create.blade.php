<x-layouts.guru title="Buat Asesmen">
    <div x-data="{ busy: false }">
        <div x-show="!busy">
            <x-ui.page-header eyebrow="Asesmen diagnostik" title="Buat asesmen dengan AI"
                description="Tulis topiknya. AI menyusun 12 soal dalam 4 level, lengkap dengan miskonsepsi di setiap pilihan salah." />

            <div class="grid gap-6 lg:grid-cols-3">
                <x-ui.card class="lg:col-span-2" padding="lg">
                    <form method="POST" action="{{ route('guru.asesmen.store') }}" class="space-y-5" @submit="busy = true">
                        @csrf
                        <x-ui.field label="Kelas" for="classroom_id" required>
                            <x-ui.select name="classroom_id" :value="$selected"
                                :options="$classrooms->mapWithKeys(fn ($c) => [$c->id => $c->name.' · '.$c->grade])->all()" required />
                        </x-ui.field>

                        <x-ui.field label="Topik" for="topic" hint="Contoh: Pecahan, Perkalian, Teks Deskripsi, Gaya dan Gerak" required>
                            <x-ui.input name="topic" placeholder="Tulis topik pembelajaran" maxlength="80" required autofocus />
                        </x-ui.field>

                        <x-ui.field label="Catatan untuk AI" for="notes" hint="Opsional. Misalnya: fokus pada soal cerita, konteks pedesaan.">
                            <x-ui.textarea name="notes" rows="3" maxlength="300" placeholder="Tulis catatan tambahan…" />
                        </x-ui.field>

                        <div class="flex justify-end">
                            <x-ui.button type="submit" variant="ai" size="lg" icon="sparkles">Buat dengan AI</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>

                <x-ui.card title="Yang akan Anda dapat">
                    <ul class="space-y-3 text-sm text-ink-soft">
                        @foreach ([
                            ['squares-2x2', '4 level kompetensi berurutan, dari paling dasar'],
                            ['light-bulb', 'Setiap pilihan salah dipetakan ke miskonsepsi'],
                            ['pencil-square', 'Anda tinjau dulu sebelum dibagikan'],
                            ['user-group', 'Siswa dikelompokkan otomatis setelah menjawab'],
                        ] as [$icon, $text])
                            <li class="flex gap-3">
                                <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-5 shrink-0 text-ai-600" aria-hidden="true" />
                                {{ $text }}
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            </div>
        </div>

        <div x-show="busy" x-cloak class="mx-auto max-w-md pt-10">
            <x-ui.ai-loading title="Menyusun asesmen diagnostik…" :interval="3500"
                :steps="['Membaca topik & jenjang', 'Menyusun 4 level kompetensi', 'Menulis 12 soal', 'Memetakan miskonsepsi', 'Memeriksa kunci jawaban']" />
            <p class="mt-4 text-center text-sm text-muted">Biasanya 10–30 detik. Jangan tutup halaman ini.</p>
        </div>
    </div>
</x-layouts.guru>
