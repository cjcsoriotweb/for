@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php

    if (! $enable || ! auth()->check()) {
        return;
    }

    $trainers = \App\Models\AiTrainer::query()
        ->active()
        ->where('show_everywhere', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
@endphp

@if ($trainers->isNotEmpty())
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-4">
        @foreach ($trainers as $trainer)
            <livewire:chat-box
                :key="'assistant-chat-'.$trainer->slug"
                :trainer="$trainer->slug"
                :title="$trainer->name"
            />
        @endforeach
    </div>
@endif




