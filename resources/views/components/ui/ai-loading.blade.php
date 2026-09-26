@props([
    'title' => 'AI sedang bekerja…',
    'steps' => ['Membaca topik', 'Menyusun soal per level', 'Memetakan miskonsepsi', 'Memeriksa ulang jawaban'],
    'interval' => 2500,
])

{{--
    Loading AI yang menjelaskan prosesnya (labor illusion): menunggu 10–20 detik
    terasa lebih singkat dan lebih dipercaya bila pengguna melihat langkah-langkahnya.
--}}
<div
    x-data="{ step: 0, steps: @js($steps) }"
    x-init="setInterval(() => { if (step < steps.length - 1) step++ }, {{ (int) $interval }})"
    role="status" aria-live="polite"
    {{ $attributes->class('card flex flex-col items-center px-6 py-10 text-center') }}>
    <span class="relative flex size-14 items-center justify-center">
        <span class="absolute inset-0 animate-ping rounded-full bg-ai-100 motion-reduce:animate-none"></span>
        <span class="relative flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-ai-500 to-brand-600 text-white">
            <x-heroicon-o-sparkles class="size-7" aria-hidden="true" />
        </span>
    </span>

    <p class="mt-5 font-semibold text-ink">{{ $title }}</p>

    <ol class="mt-4 w-full max-w-xs space-y-2 text-left text-sm">
        <template x-for="(label, i) in steps" :key="i">
            <li class="flex items-center gap-2 transition-colors"
                :class="i < step ? 'text-success-700' : (i === step ? 'text-ink font-medium' : 'text-muted')">
                <span class="flex size-5 shrink-0 items-center justify-center">
                    <x-heroicon-m-check-circle x-show="i < step" class="size-5" aria-hidden="true" />
                    <x-heroicon-o-arrow-path x-show="i === step" class="size-4 animate-spin" aria-hidden="true" />
                    <span x-show="i > step" class="size-1.5 rounded-full bg-current" aria-hidden="true"></span>
                </span>
                <span x-text="label"></span>
            </li>
        </template>
    </ol>
</div>
