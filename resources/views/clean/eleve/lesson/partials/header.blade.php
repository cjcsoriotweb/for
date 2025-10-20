{{-- En-tête de la leçon --}}
<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $lesson->title }}
                </h1>
                @if($lessonContent && $lessonContent->description)
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $lessonContent->description }}
                </p>
                @endif
            </div>
            <div class="text-right">
                @if($lessonProgress)
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Statut
                </div>
                <div
                    class="text-lg font-semibold {{ $lessonProgress->pivot->status === 'completed' ? 'text-green-600' : 'text-blue-600' }}"
                >
                    {{ $lessonProgress->pivot->status === 'completed' ? 'Terminée' : 'En cours' }}
                </div>
                @else
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Statut
                </div>
                <div class="text-lg font-semibold text-gray-600">
                    Non commencée
                </div>
                @endif
            </div>
        </div>

        {{-- Progression de la leçon --}}
        @if($lessonProgress)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            @if($lessonType === 'text' && $lessonProgress->pivot->read_percent
            !== null)
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Progression de lecture
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $lessonProgress->pivot->read_percent }}%
                </div>
            </div>
            @endif @if($lessonType === 'quiz' &&
            $lessonProgress->pivot->attempts > 0)
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Tentatives
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $lessonProgress->pivot->attempts }}
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Meilleur score
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $lessonProgress->pivot->best_score ?? 0 }}%
                </div>
            </div>
            @endif @if($lessonProgress->pivot->started_at)
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Dernière activité
                </div>
                <div class="text-sm text-gray-900 dark:text-white">
                    {{ $lessonProgress->pivot->last_activity_at->diffForHumans() }}
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
