<x-eleve-layout :team="$team">
    {{-- Messages de notification --}}
    @if(session('success'))
    <div
        class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"
    >
        {{ session("success") }}
    </div>
    @endif @if(session('warning'))
    <div
        class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg"
    >
        {{ session("warning") }}
    </div>
    @endif @if(session('error'))
    <div
        class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"
    >
        {{ session("error") }}
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Formation Header -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $formationWithProgress->title }}
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            {{ $formationWithProgress->description }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Progression
                        </div>
                        <div
                            class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                        >
                            {{ $progress["percentage"] ?? 0 }}%
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div
                    class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4"
                >
                    <div
                        class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: {{ $progress['percentage'] ?? 0 }}%"
                    ></div>
                </div>

                <!-- Formation Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Chapitres
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $progress["total_chapters"] ?? 0 }}
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Leçons complétées
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $progress["completed_lessons"] ?? 0 }}
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Quiz réussis
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $progress["passed_quizzes"] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chapters Section -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-xl font-semibold mb-4">Chapitres</h2>

                @if($formationWithProgress->chapters &&
                $formationWithProgress->chapters->count() > 0)
                <div class="space-y-4">
                    @foreach($formationWithProgress->chapters as $chapter)
                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
                    >
                        <div class="flex justify-between items-center mb-2">
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-white"
                            >
                                {{ $chapter->title }}
                            </h3>
                            @if($chapter->pivot->completed ?? false)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                            >
                                Terminé
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200"
                            >
                                En cours
                            </span>
                            @endif
                        </div>

                        <p class="text-gray-600 dark:text-gray-400 mb-3">
                            {{ $chapter->description }}
                        </p>

                        <!-- Lessons in Chapter -->
                        @if($chapter->lessons && $chapter->lessons->count() > 0)
                        <div class="mt-3">
                            <h4
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Leçons:
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($chapter->lessons as $lesson)
                                <div class="flex items-center space-x-2">
                                    @if($lesson->pivot->completed ?? false)
                                    <svg
                                        class="w-4 h-4 text-green-500"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                    @else
                                    <svg
                                        class="w-4 h-4 text-gray-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                    @endif
                                    <a
                                        href="{{
                                            route('eleve.lesson.show', [
                                                $team,
                                                $formationWithProgress,
                                                $chapter,
                                                $lesson
                                            ])
                                        }}"
                                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline transition-colors duration-200"
                                    >
                                        {{ $lesson->title }}
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400">
                    Aucun chapitre disponible pour cette formation.
                </p>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between items-center">
            <a
                href="{{ route('eleve.index', $team) }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
                Retour à l'accueil
            </a>

            @if(($progress['percentage'] ?? 0) < 100)
            <form
                method="POST"
                action="{{
                    route('eleve.formation.reset-progress', [
                        $team,
                        $formationWithProgress
                    ])
                }}"
                class="inline"
            >
                @csrf @method('POST')
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser votre progression dans cette formation ?')"
                >
                    Réinitialiser la progression
                </button>
            </form>
            @endif
        </div>
    </div>
</x-eleve-layout>
