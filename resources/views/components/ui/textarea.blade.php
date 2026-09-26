@props([
    'name',
    'value' => null,
    'rows' => 4,
])

@php
    $invalid = isset($errors) && $errors->has($name);
@endphp

<textarea
    name="{{ $name }}"
    id="{{ $attributes->get('id', $name) }}"
    rows="{{ $rows }}"
    @if ($invalid) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->except('id')->class('input resize-y') }}>{{ old($name, $value) }}</textarea>
