@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    if (! $enable || ! auth()->check()) {
        return;
    }
@endphp

<livewire:chat-box />
