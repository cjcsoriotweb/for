@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    if (! $enable || ! auth()->check()) {
        return;
    }

    $trainers = config('ai.trainers', []);
@endphp

@if (! empty($trainers))
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-4">
        @foreach ($trainers as $slug => $trainer)
            <livewire:chat-box
                :key="'assistant-chat-'.$slug"
                :trainer="$slug"
                :title="$trainer['name'] ?? ucfirst($slug)"
            />
        @endforeach
    </div>
@endif
