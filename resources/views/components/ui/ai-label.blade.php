@props(['text' => 'Dibuat AI · periksa sebelum dipakai'])

{{-- Transparansi: semua konten buatan AI wajib diberi label ini. --}}
<x-ui.badge tone="ai" icon="sparkles" {{ $attributes }}>{{ $text }}</x-ui.badge>
