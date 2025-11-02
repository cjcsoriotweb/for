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
    <button
        type="button"
        class="mb-2 flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
        data-assistant-launch="{{ $slug }}"
        title="{{ $name }}"
        aria-label="Ouvrir l'assistant {{ $name }}"
        onclick="window.Livewire?.emit && window.Livewire.emit('launchAssistant', '{{ $slug }}')"
    >
        <span class="font-bold text-lg">{{ $initials }}</span>
    </button>
    @push('chatboxes')
        <livewire:chat-box :key="'assistant-chat-'.$slug" :trainer="$slug" :title="$name" />
    @endpush
@endif

