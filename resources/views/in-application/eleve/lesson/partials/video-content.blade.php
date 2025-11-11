{{-- Contenu video --}}
<div class="space-y-6">
    <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $lesson->getName() }}
        </h2>

    </div>

    {{-- Composant Livewire pour la gestion video --}}
    @livewire('eleve.video-player', [
        'team' => $team,
        'formation' => $formation,
        'chapter' => $chapter,
        'lesson' => $lesson,
        'lessonContent' => $lessonContent,
    ])

    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('A propos de cette vidéo') }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $lessonContent->description }}</p>
    </div>

</div>
