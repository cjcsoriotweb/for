{{-- Contenu video --}}
<div class="space-y-6">
    <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $lesson->getName() }}
        </h2>
        @if(! empty($lessonContent?->description))
            <p class="mt-2 text-gray-600 dark:text-gray-300">
                {{ $lessonContent->description }}
            </p>
        @endif
    </div>

    {{-- Composant Livewire pour la gestion video --}}
    @livewire('eleve.video-player', [
        'team' => $team,
        'formation' => $formation,
        'chapter' => $chapter,
        'lesson' => $lesson,
        'lessonContent' => $lessonContent,
    ])
</div>
