<x-layouts.guru :title="$assessment->topic">
    <x-ui.page-header :eyebrow="$assessment->classroom->name.' · '.$assessment->grade" :title="'Asesmen '.$assessment->topic"
        description="Tinjau soal, kunci jawaban, dan miskonsepsi yang dipetakan sebelum dibagikan ke siswa.">
        <x-slot:actions>
            @if ($assessment->isPublished())
                <x-ui.button variant="secondary" icon="chart-bar" :href="route('guru.asesmen.hasil', $assessment)">Lihat hasil</x-ui.button>
            @else
                <form method="POST" action="{{ route('guru.asesmen.publish', $assessment) }}">
                    @csrf
                    <x-ui.button type="submit" icon="paper-airplane">Publikasikan</x-ui.button>
                </form>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mb-6 flex flex-wrap items-center gap-3">
        @if ($assessment->source === 'fixture')
            <x-ui.alert tone="warning" class="w-full">
                Mode demo: soal contoh <strong>Pecahan kelas 5</strong> dipakai karena AI tidak aktif (<code>LLM_FAKE=true</code>) atau tidak merespons.
            </x-ui.alert>
        @else
            <x-ui.ai-label />
        @endif
        @if ($assessment->isPublished())
            <x-ui.alert tone="success" class="w-full">
                Siswa masuk di <strong>{{ route('siswa.join') }}</strong> dengan kode kelas
                <strong class="font-mono tracking-widest">{{ $assessment->classroom->code }}</strong>.
            </x-ui.alert>
        @endif
    </div>

    <div class="space-y-6">
        @foreach ($assessment->questions->groupBy('level') as $level => $questions)
            <x-ui.card>
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <x-ui.level-badge :level="$level" />
                    <h2 class="font-semibold">{{ $assessment->levelLabel($level) }}</h2>
                </div>

                <ol class="space-y-5">
                    @foreach ($questions as $question)
                        <li class="rounded-xl border border-line p-4">
                            <p class="font-medium text-ink">{{ $loop->iteration }}. {{ $question->stem }}</p>
                            <ul class="mt-3 space-y-2 text-sm">
                                @foreach ($question->options as $option)
                                    @php $correct = $option['key'] === $question->answer_key; @endphp
                                    <li @class([
                                        'flex flex-col gap-1 rounded-lg px-3 py-2 sm:flex-row sm:items-center sm:justify-between',
                                        'bg-success-50 text-success-700 font-medium' => $correct,
                                        'bg-subtle text-ink-soft' => ! $correct,
                                    ])>
                                        <span class="flex items-center gap-2">
                                            @if ($correct)
                                                <x-heroicon-m-check-circle class="size-4" aria-hidden="true" />
                                            @endif
                                            {{ $option['key'] }}. {{ $option['text'] }}
                                            @if ($correct) <span class="sr-only">(kunci jawaban)</span> @endif
                                        </span>
                                        @if (! $correct && $option['misconception'])
                                            <span class="flex items-center gap-1 text-xs text-muted">
                                                <x-heroicon-m-light-bulb class="size-4 shrink-0 text-warning-600" aria-hidden="true" />
                                                {{ $option['misconception'] }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @if ($question->explanation)
                                <p class="mt-3 text-sm text-muted"><span class="font-medium text-ink-soft">Pembahasan:</span> {{ $question->explanation }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </x-ui.card>
        @endforeach
    </div>
</x-layouts.guru>
