{{-- Contenu de la leçon --}}
<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        @if($lessonType === 'video')
        {{-- Contenu vidéo --}}
        <div class="mb-6">
            @if($lessonContent->video_url)
            <div
                class="aspect-video mb-4 bg-black rounded-lg overflow-hidden relative"
            >
                <video
                    controls
                    controlsList="nodownload"
                    class="w-full h-full"
                    id="lesson-video"
                    preload="metadata"
                    poster="{{ asset('images/video-poster.jpg') }}"
                    oncontextmenu="return false;"
                >
                    <source
                        src="{{ asset('storage/' . $lessonContent->video_path) }}"
                        type="video/mp4"
                    />
                    <source
                        src="{{ asset('storage/' . $lessonContent->video_path) }}"
                        type="video/webm"
                    />
                    Votre navigateur ne supporte pas la lecture de vidéos.
                </video>

                {{-- Loader personnalisé --}}
                <div
                    id="video-loader"
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"
                    ></div>
                </div>

                {{-- Message d'erreur --}}
                <div
                    id="video-error"
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden"
                >
                    <div class="text-white text-center">
                        <p class="mb-2">Erreur de chargement de la vidéo</p>
                        <button
                            onclick="retryVideo()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
                        >
                            Réessayer
                        </button>
                    </div>
                </div>
            </div>

            {{-- Contrôles vidéo personnalisés --}}
            <div
                id="custom-video-controls"
                class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
            >
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-4">
                        <button
                            id="play-pause-btn"
                            class="text-blue-600 hover:text-blue-800"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path id="play-icon" d="M8 5v10l8-5-8-5z" />
                                <path
                                    id="pause-icon"
                                    class="hidden"
                                    d="M6 4h2v12H6V4zm6 0h2v12h-2V4z"
                                />
                            </svg>
                        </button>

                        <button
                            id="mute-btn"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    id="unmute-icon"
                                    d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.29 13H2a1 1 0 01-1-1V7a1 1 0 011-1h2.29l4.093-3.816a1 1 0 011.617.816z"
                                />
                                <path
                                    id="mute-icon"
                                    class="hidden"
                                    d="M16.707 14.293a1 1 0 01-1.414 1.414l-1.414-1.414-1.414 1.414a1 1 0 01-1.414-1.414l1.414-1.414-1.414-1.414a1 1 0 011.414-1.414l1.414 1.414 1.414-1.414a1 1 0 011.414 1.414l-1.414 1.414 1.414 1.414z"
                                />
                            </svg>
                        </button>

                        <div class="flex-1 mx-4">
                            <div
                                class="bg-gray-200 dark:bg-gray-600 rounded-full h-2 cursor-pointer"
                                id="progress-bar"
                            >
                                <div
                                    class="bg-blue-600 h-2 rounded-full"
                                    style="width: 0%"
                                    id="progress"
                                ></div>
                            </div>
                        </div>

                        <span
                            id="current-time"
                            class="text-sm text-gray-600 dark:text-gray-400"
                            >0:00</span
                        >
                        <span class="text-sm text-gray-400">/</span>
                        <span
                            id="duration"
                            class="text-sm text-gray-600 dark:text-gray-400"
                            >0:00</span
                        >
                    </div>

                    <div class="flex items-center space-x-2">
                        <button
                            id="fullscreen-btn"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Contrôles de vitesse --}}
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400"
                        >Vitesse:</span
                    >
                    <select
                        id="speed-select"
                        class="text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded px-2 py-1"
                    >
                        <option value="0.5">0.5x</option>
                        <option value="0.75">0.75x</option>
                        <option value="1" selected>Normal</option>
                        <option value="1.25">1.25x</option>
                        <option value="1.5">1.5x</option>
                        <option value="2">2x</option>
                    </select>
                </div>
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
