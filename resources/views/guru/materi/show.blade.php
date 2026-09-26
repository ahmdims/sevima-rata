@php
    $content = $material->content;
    $md = fn ($text) => Str::markdown((string) $text, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
@endphp

<x-layouts.guru :title="$content['title']">
    <div class="no-print">
        <x-ui.button variant="ghost" size="sm" icon="arrow-left" :href="route('guru.asesmen.hasil', $assessment)" class="mb-4 -ml-3">Kembali ke hasil</x-ui.button>
    </div>

    <x-ui.page-header :eyebrow="$assessment->classroom->name.' · '.$assessment->topic" :title="$content['title']">
        <x-slot:actions>
            <form method="POST" action="{{ route('guru.materi.store', [$assessment, $level]) }}" class="no-print"
                x-data="{ busy: false }" @submit="busy = true">
                @csrf
                <x-ui.button type="submit" variant="secondary" icon="arrow-path" x-bind:disabled="busy">
                    <span x-show="!busy">Buat ulang</span><span x-show="busy" x-cloak>Menyusun…</span>
                </x-ui.button>
            </form>
            <x-ui.button icon="printer" onclick="window.print()" class="no-print">Cetak</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <x-ui.level-badge :level="$level" />
        <span class="text-sm text-muted">{{ $students->count() }} siswa: {{ $students->pluck('student_name')->implode(', ') }}</span>
        @if ($material->source === 'ai')
            <x-ui.ai-label />
        @else
            <x-ui.badge tone="warning">Materi contoh (mode demo)</x-ui.badge>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-ui.card title="Konsep">
                <div class="prose prose-slate max-w-none prose-strong:text-ink">{!! $md($content['concept_md']) !!}</div>
            </x-ui.card>

            <x-ui.card title="Contoh">
                <div class="prose prose-slate max-w-none">{!! $md($content['examples_md']) !!}</div>
            </x-ui.card>

            <x-ui.card title="Latihan bertahap">
                <ol class="space-y-3">
                    @foreach ($content['exercises'] as $i => $exercise)
                        <li class="rounded-xl border border-line p-4" x-data="{ open: false }">
                            <div class="flex items-start gap-3">
                                <span class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-sm font-semibold text-brand-700">{{ $i + 1 }}</span>
                                <p class="flex-1 text-ink">{{ $exercise['q'] }}</p>
                                <button type="button" class="no-print text-sm font-medium text-brand-600 hover:text-brand-700" @click="open = !open"
                                    x-text="open ? 'Sembunyikan' : 'Kunci'" :aria-expanded="open"></button>
                            </div>
                            <p class="mt-2 pl-10 text-sm text-success-700" x-show="open" x-cloak>Jawaban: {{ $exercise['answer'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </x-ui.card>
        </div>

        <x-ui.card title="Tips mengajar" class="h-fit bg-ai-50/40">
            <div class="prose prose-sm prose-slate max-w-none">{!! $md($content['teacher_tips_md']) !!}</div>
        </x-ui.card>
    </div>
</x-layouts.guru>
