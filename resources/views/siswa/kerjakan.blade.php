<x-layouts.siswa :title="'Soal '.$number">
    <div class="mb-5">
        <div class="mb-2 flex items-center justify-between text-sm">
            <span class="font-medium text-ink">Hai, {{ $attempt->student_name }}!</span>
            <span class="text-muted tabular-nums">Soal {{ $number }} dari {{ $total }}</span>
        </div>
        <x-ui.progress :value="$number - 1" :max="$total" :segments="$total" />
    </div>

    <form method="POST" action="{{ route('siswa.kerjakan.answer', $attempt) }}" x-data="{ sending: false }" @submit="sending = true">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">

        <x-ui.card class="animate-fade-up" padding="lg">
            <fieldset>
                <legend class="mb-5 text-lg leading-relaxed font-medium text-ink">{{ $question->stem }}</legend>
                <div class="space-y-3">
                    @foreach ($question->options as $option)
                        <x-ui.choice name="answer" :value="$option['key']" :key="$option['key']">{{ $option['text'] }}</x-ui.choice>
                    @endforeach
                </div>
            </fieldset>
        </x-ui.card>

        <x-ui.button type="submit" size="lg" class="mt-5 w-full" icon-right="arrow-right" x-bind:disabled="sending">
            {{ $number === $total ? 'Kirim jawaban' : 'Lanjut' }}
        </x-ui.button>
    </form>

    <p class="mt-4 text-center text-sm text-muted">Tidak apa-apa kalau belum yakin. Pilih jawaban yang menurutmu paling tepat.</p>
</x-layouts.siswa>
