@props([
    'name',
    'options' => [],   // [value => label]
    'value' => null,
    'placeholder' => null,
])

@php
    $invalid = isset($errors) && $errors->has($name);
    $selected = (string) old($name, $value);
@endphp

<div class="relative">
    <select
        name="{{ $name }}"
        id="{{ $attributes->get('id', $name) }}"
        @if ($invalid) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->except('id')->class('input appearance-none pr-10') }}>
        @if ($placeholder)
            <option value="" disabled @selected($selected === '')>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    <x-heroicon-m-chevron-down class="pointer-events-none absolute top-1/2 right-3 size-5 -translate-y-1/2 text-muted" aria-hidden="true" />
</div>
