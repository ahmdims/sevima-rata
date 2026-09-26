@props([
    'name',
    'type' => 'text',
    'value' => null,
])

@php
    $invalid = isset($errors) && $errors->has($name);
@endphp

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $attributes->get('id', $name) }}"
    value="{{ old($name, $value) }}"
    @if ($invalid) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->except('id')->class('input') }}>
