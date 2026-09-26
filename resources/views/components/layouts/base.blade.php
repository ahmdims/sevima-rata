@props(['title' => null, 'bodyClass' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    <meta name="description" content="RATA — asesmen diagnostik berbasis AI agar setiap anak belajar di level yang tepat.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|instrument-serif:400,400i" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body {{ $attributes->merge(['class' => 'min-h-screen '.$bodyClass]) }}>
    {{ $slot }}
</body>
</html>
