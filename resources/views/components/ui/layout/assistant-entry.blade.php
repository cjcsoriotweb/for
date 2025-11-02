@props([
    'slug',
    'name',
    'notifications' => 0,
    'locked' => false,
])

@php
    $initials = strtoupper(mb_substr($name ?? '', 0, 2));
@endphp

@if (! empty($slug))
    @push('chatboxes')


        <livewire:chat-box :key="'assistant-chat-'.$slug" :trainer="$slug" :title="$name" />
    @endpush
@endif

