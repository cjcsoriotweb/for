{{-- Contenu de la leçon --}}
<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        @if($lessonType === 'video')
        {{-- Contenu vidéo --}}
        <div class="mb-6">
            @if($lessonContent->video_url)
            <div class="aspect-video mb-4">
                <video
                    controls
                    class="w-full h-full rounded-lg"
                    id="lesson-video"
                >
                    <source
                        src="{{ asset('storage/' . $lessonContent->video_path) }}"
                        type="video/mp4"
                    />
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>
            </div>
            @endif @if($lessonContent->duration_minutes)
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Durée: {{ $lessonContent->duration_minutes }} minutes
            </div>
            @endif
        </div>

        {{-- Actions vidéo --}}
        <div class="flex justify-between items-center">
            <form
                method="POST"
                action="{{
                    route('eleve.lesson.start', [
                        $team,
                        $formation,
                        $chapter,
                        $lesson
                    ])
                }}"
                class="inline"
            >
                @csrf
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Commencer la leçon
                </button>
            </form>

            <form
                method="POST"
                action="{{
                    route('eleve.lesson.complete', [
                        $team,
                        $formation,
                        $chapter,
                        $lesson
                    ])
                }}"
                class="inline"
            >
                @csrf
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                >
                    Marquer comme terminée
                </button>
            </form>
        </div>

        @elseif($lessonType === 'text')
        {{-- Contenu texte --}}
        <div class="prose dark:prose-invert max-w-none mb-6">
            {!! nl2br(e($lessonContent->content)) !!}
        </div>

        {{-- Barre de progression de lecture --}}
        <div class="mb-6">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Progression de lecture</span>
                <span>{{ $lessonProgress->pivot->read_percent ?? 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div
                    class="bg-blue-600 h-2 rounded-full"
                    style="width: {{ $lessonProgress->pivot->read_percent ?? 0 }}%"
                ></div>
            </div>
        </div>

        {{-- Actions texte --}}
        <div class="flex justify-between items-center">
            <form
                method="POST"
                action="{{
                    route('eleve.lesson.start', [
                        $team,
                        $formation,
                        $chapter,
                        $lesson
                    ])
                }}"
                class="inline"
            >
                @csrf
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Commencer la lecture
                </button>
            </form>

            <div class="flex space-x-2">
                @if($lessonContent->allow_download)
                <a
                    href="{{
                        route('eleve.lesson.download', [
                            $team,
                            $formation,
                            $chapter,
                            $lesson
                        ])
                    }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block"
                >
                    Télécharger
                </a>
                @endif

                <form
                    method="POST"
                    action="{{
                        route('eleve.lesson.complete', [
                            $team,
                            $formation,
                            $chapter,
                            $lesson
                        ])
                    }}"
                    class="inline"
                >
                    @csrf
                    <button
                        type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Marquer comme lue
                    </button>
                </form>
            </div>
        </div>

        @elseif($lessonType === 'quiz')
        {{-- Contenu quiz --}}
        <div class="mb-6">
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4"
            >
                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
                    Informations du Quiz
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Score de passage:</span>
                        {{ $lessonContent->passing_score }}%
                    </div>
                    @if($lessonContent->max_attempts > 0)
                    <div>
                        <span class="font-medium">Tentatives max:</span>
                        {{ $lessonContent->max_attempts }}
                    </div>
                    @endif
                    <div>
                        <span class="font-medium">Questions:</span>
                        {{ $lessonContent->quizQuestions->count() }}
                    </div>
                </div>
            </div>

            @if($lessonProgress && $lessonProgress->pivot->attempts >=
            $lessonContent->max_attempts && $lessonContent->max_attempts > 0)
            <div
                class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4"
            >
                <p class="text-red-800 dark:text-red-200">
                    Vous avez atteint le nombre maximum de tentatives pour ce
                    quiz.
                </p>
            </div>
            @else
            <div class="text-center">
                <a
                    href="{{
                        route('eleve.lesson.quiz.attempt', [
                            $team,
                            $formation,
                            $chapter,
                            $lesson
                        ])
                    }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-block"
                >
                    Commencer le Quiz
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
