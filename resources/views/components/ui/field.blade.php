@props([
    'label',
    'for',
    'hint' => null,
    'required' => false,
])

{{-- Pembungkus label + kontrol + hint + error. Error diambil otomatis dari $errors. --}}
@php
    $error = isset($errors) ? $errors->first($for) : null;
@endphp

<div {{ $attributes->class('space-y-1.5') }}>
    <label for="{{ $for }}" class="block text-sm font-medium text-ink">
        {{ $label }}
        @if ($required)
            <span class="text-danger-600" aria-hidden="true">*</span>
        @endif
    </label>

    {{ $slot }}

    @if ($error)
        <p id="{{ $for }}-error" class="flex items-center gap-1 text-xs font-medium text-danger-700">
            <x-heroicon-m-exclamation-circle class="size-4" aria-hidden="true" />
            {{ $error }}
        </p>
    @elseif ($hint)
        <p id="{{ $for }}-hint" class="text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
