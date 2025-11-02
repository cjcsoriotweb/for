@props([
    'slug',
    'name',
    'notifications' => 0,
    'locked' => false,
])

@php
    $initials = strtoupper(mb_substr($name ?? '', 0, 2));
@endphp

@php
    // Push chatboxes only when we're on a formation page (not on formation lesson pages).
    $currentRoute = request()->route();
    $isFormationPage = false;
    if ($currentRoute && $currentRoute->getName()) {
        $routeName = $currentRoute->getName();
        $isFormationPage = str_contains($routeName, 'eleve.formation') && ! str_contains($routeName, 'eleve.formation.lesson');
    }
@endphp

@if (! empty($slug) && $isFormationPage)
    @push('chatboxes')
        <livewire:chat-box :key="'assistant-chat-'.$slug" :trainer="$slug" :title="$name" />
    @endpush
@endif

