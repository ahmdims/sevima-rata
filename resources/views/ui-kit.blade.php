@php
    $swatches = [
        'Netral' => [
            ['canvas', 'bg-canvas', '#F5F7FB', 'Latar halaman'],
            ['surface', 'bg-surface', '#FFFFFF', 'Kartu, panel'],
            ['line', 'bg-line', '#E5E8F0', 'Border dekoratif'],
            ['field', 'bg-field', '#858FA3', 'Border input · 3.3:1'],
            ['muted', 'bg-muted', '#5B6478', 'Caption · 5.9:1'],
            ['ink', 'bg-ink', '#0F172A', 'Teks utama · 17.9:1'],
        ],
        'Brand & AI' => [
            ['brand-50', 'bg-brand-50', '#EEF4FF', 'Latar aktif'],
            ['brand-500', 'bg-brand-500', '#3B6CF6', 'Focus ring'],
            ['brand-600', 'bg-brand-600', '#2553E8', 'Aksi utama · 6.0:1'],
            ['brand-700', 'bg-brand-700', '#1D41C4', 'Hover'],
            ['ai-50', 'bg-ai-50', '#F5F3FF', 'Latar konten AI'],
            ['ai-600', 'bg-ai-600', '#7C3AED', 'Aksi AI · 5.7:1'],
        ],
        'Semantik sistem' => [
            ['success-600', 'bg-success-600', '#059669', 'Berhasil'],
            ['warning-600', 'bg-warning-600', '#D97706', 'Perhatian'],
            ['danger-600', 'bg-danger-600', '#DC2626', 'Error sistem saja'],
        ],
    ];
@endphp

<x-layouts.base title="UI Kit">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <x-ui.page-header eyebrow="Design System" title="RATA UI Kit"
            description="Semua token & komponen yang dipakai di aplikasi. Dokumentasi: docs/DESIGN.md">
            <x-slot:actions>
                <x-ui.button variant="secondary" :href="url('/')" icon="arrow-left">Beranda</x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <div class="space-y-8">
            {{-- Warna --}}
            <x-ui.card title="Warna" description="Setiap pasangan teks/latar lolos WCAG 2.2 AA.">
                <div class="space-y-6">
                    @foreach ($swatches as $group => $items)
                        <div>
                            <p class="eyebrow mb-3">{{ $group }}</p>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                                @foreach ($items as [$name, $class, $hex, $use])
                                    <div>
                                        <div class="h-14 rounded-xl border border-line {{ $class }}"></div>
                                        <p class="mt-2 text-sm font-medium">{{ $name }}</p>
                                        <p class="text-xs text-muted">{{ $hex }} · {{ $use }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div>
                        <p class="eyebrow mb-3">Level — skala pertumbuhan (tanpa merah)</p>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                            @foreach (range(0, 4) as $level)
                                <div class="rounded-xl border border-line p-3">
                                    <div class="h-10 rounded-lg bg-level-{{ $level }}"></div>
                                    <div class="mt-3 flex flex-col items-start gap-1.5">
                                        <x-ui.level-badge :level="$level" />
                                        <x-ui.level-badge :level="$level" audience="siswa" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Tipografi --}}
            <x-ui.card title="Tipografi" description="Instrument Serif untuk momen emosional (hero, hasil siswa); Inter untuk seluruh UI.">
                <div class="space-y-4">
                    <p class="font-display text-5xl leading-tight">Setiap anak belajar <em class="text-brand-600">di level yang tepat.</em></p>
                    <p class="text-3xl font-semibold tracking-tight">Judul halaman · 30/semibold</p>
                    <p class="text-xl font-semibold">Judul bagian · 20/semibold</p>
                    <p class="text-base font-semibold">Judul kartu · 16/semibold</p>
                    <p class="max-w-prose text-base text-ink-soft">Teks isi 16px dengan tinggi baris 1.5. Paragraf dibatasi ±65–75 karakter per baris agar nyaman dibaca guru maupun siswa.</p>
                    <p class="text-sm text-muted">Caption / meta · 14px muted</p>
                    <p class="eyebrow">Eyebrow · 12px uppercase</p>
                    <p class="text-4xl font-semibold tabular-nums">1.284</p>
                </div>
            </x-ui.card>

            <div class="grid gap-8 lg:grid-cols-2">
                {{-- Tombol --}}
                <x-ui.card title="Tombol" description="Satu tombol primary per layar.">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button>Primary</x-ui.button>
                        <x-ui.button variant="secondary">Secondary</x-ui.button>
                        <x-ui.button variant="ghost">Ghost</x-ui.button>
                        <x-ui.button variant="dark">Dark</x-ui.button>
                        <x-ui.button variant="ai" icon="sparkles">Buat dengan AI</x-ui.button>
                        <x-ui.button variant="danger" icon="trash">Hapus</x-ui.button>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <x-ui.button size="sm">Small</x-ui.button>
                        <x-ui.button size="md">Medium</x-ui.button>
                        <x-ui.button size="lg" icon-right="arrow-right">Large (siswa)</x-ui.button>
                        <x-ui.button loading>Menyimpan…</x-ui.button>
                        <x-ui.button disabled>Disabled</x-ui.button>
                    </div>
                </x-ui.card>

                {{-- Badge --}}
                <x-ui.card title="Badge" description="Status singkat. Selalu teks, tidak warna saja.">
                    <div class="flex flex-wrap gap-2">
                        <x-ui.badge>Draf</x-ui.badge>
                        <x-ui.badge tone="brand">Dipublikasikan</x-ui.badge>
                        <x-ui.badge tone="success" icon="check">Selesai</x-ui.badge>
                        <x-ui.badge tone="warning">Menunggu</x-ui.badge>
                        <x-ui.badge tone="danger">Gagal</x-ui.badge>
                        <x-ui.ai-label />
                    </div>
                    <div class="mt-5 flex items-center gap-3">
                        <x-ui.avatar name="Dimas Pratama" size="sm" />
                        <x-ui.avatar name="Sari Wulandari" />
                        <x-ui.avatar name="Ayu" size="lg" />
                    </div>
                </x-ui.card>
            </div>

            {{-- Form --}}
            <x-ui.card title="Form" description="Label di atas, hint di bawah, error spesifik + cara memperbaiki.">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Topik" for="kit-topic" hint="Contoh: Pecahan, Perkalian, Teks Deskripsi" required>
                        <x-ui.input name="kit-topic" placeholder="Tulis topik pembelajaran" />
                    </x-ui.field>
                    <x-ui.field label="Jenjang" for="kit-grade" required>
                        <x-ui.select name="kit-grade" placeholder="Pilih kelas" :options="['4' => 'Kelas 4 SD', '5' => 'Kelas 5 SD', '6' => 'Kelas 6 SD']" />
                    </x-ui.field>
                    <x-ui.field label="Catatan untuk AI" for="kit-notes" hint="Opsional. Misalnya: fokus pada soal cerita." class="sm:col-span-2">
                        <x-ui.textarea name="kit-notes" rows="3" placeholder="Tulis catatan tambahan…" />
                    </x-ui.field>
                </div>
            </x-ui.card>

            {{-- Statistik --}}
            <div>
                <p class="eyebrow mb-3">Kartu statistik</p>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ui.stat-card featured label="Siswa mengerjakan" value="28" icon="user-group" hint="dari 32 siswa di kelas" />
                    <x-ui.stat-card label="Perlu pendampingan" value="7" icon="hand-raised" hint="Level Pra-Dasar & Dasar" />
                    <x-ui.stat-card label="Siap pengayaan" value="5" icon="rocket-launch" hint="Level Mahir" />
                    <x-ui.stat-card label="Miskonsepsi utama" value="3" icon="light-bulb" hint="Menjumlah penyebut langsung" />
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <x-ui.card title="Distribusi level">
                    <x-slot:actions><x-ui.badge tone="brand">28 siswa</x-ui.badge></x-slot:actions>
                    <x-ui.level-distribution :counts="[0 => 3, 1 => 4, 2 => 9, 3 => 7, 4 => 5]" :pending="4" />
                </x-ui.card>

                <x-ui.card title="Progress">
                    <div class="space-y-5">
                        <x-ui.progress label="Soal 5 dari 12" :value="5" :max="12" :segments="12" show-value />
                        <x-ui.progress label="Pemahaman konsep" :value="72" show-value tone="level-3" />
                        <x-ui.progress label="Selesai mengerjakan" :value="28" :max="32" show-value tone="ai" />
                    </div>
                </x-ui.card>
            </div>

            {{-- Umpan balik --}}
            <x-ui.card title="Alert">
                <div class="space-y-3">
                    <x-ui.alert tone="info">Kode kelas bisa dibagikan ke siswa lewat WhatsApp.</x-ui.alert>
                    <x-ui.alert tone="success" title="Asesmen dipublikasikan">Siswa sudah bisa mengerjakan dengan kode <strong>RATA7K</strong>.</x-ui.alert>
                    <x-ui.alert tone="warning">4 siswa belum mengerjakan.</x-ui.alert>
                    <x-ui.alert tone="danger" title="AI tidak merespons">Coba lagi dalam beberapa detik, atau gunakan soal contoh.</x-ui.alert>
                    <x-ui.alert tone="ai" title="Saran AI">Kelompok L1 sering menjumlahkan penyebut. Mulai dengan model gambar.</x-ui.alert>
                </div>
            </x-ui.card>

            <div class="grid gap-8 lg:grid-cols-2">
                <x-ui.ai-loading title="Menyusun asesmen diagnostik…" />
                <x-ui.empty-state icon="clipboard-document-list" title="Belum ada asesmen"
                    description="Buat asesmen pertama. AI akan menyusun soal diagnostik dalam kurang dari satu menit.">
                    <x-slot:action><x-ui.button variant="ai" icon="sparkles">Buat Asesmen</x-ui.button></x-slot:action>
                </x-ui.empty-state>
            </div>

            {{-- Pola siswa --}}
            <div class="grid gap-8 lg:grid-cols-2">
                <x-ui.card title="Pilihan jawaban (siswa)" description="Target sentuh ≥56px, satu soal per layar.">
                    <x-ui.progress :value="5" :max="12" :segments="12" class="mb-5" />
                    <p class="mb-4 text-lg font-medium">Manakah yang nilainya sama dengan ½?</p>
                    <div class="space-y-3">
                        <x-ui.choice name="kit-q" value="a" key="A">2/4</x-ui.choice>
                        <x-ui.choice name="kit-q" value="b" key="B" checked>1/3</x-ui.choice>
                        <x-ui.choice name="kit-q" value="c" key="C">2/3</x-ui.choice>
                    </div>
                    <x-ui.button size="lg" class="mt-5 w-full" icon-right="arrow-right">Lanjut</x-ui.button>
                </x-ui.card>

                <x-ui.card title="Hasil siswa" description="Kekuatan dulu, lalu langkah berikutnya. Tanpa skor.">
                    <div class="rounded-2xl bg-aurora p-6 text-center">
                        <x-ui.level-badge :level="2" audience="siswa" />
                        <p class="mt-3 font-display text-3xl leading-tight">Hebat, Dimas! <em class="text-brand-600">Kamu sedang tumbuh.</em></p>
                        <p class="mt-2 text-sm text-ink-soft">Kamu sudah jago membandingkan pecahan. Yuk, latihan menjumlah pecahan berpenyebut sama.</p>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-layouts.base>
