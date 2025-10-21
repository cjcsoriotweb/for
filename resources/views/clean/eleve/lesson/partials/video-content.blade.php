{{-- Contenu vidéo --}}
@if($lessonContent->video_path)
<div class="aspect-video mb-4 bg-black rounded-lg overflow-hidden relative">
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
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path id="play-icon" d="M8 5v10l8-5-8-5z" />
                    <path
                        id="pause-icon"
                        class="hidden"
                        d="M6 4h2v12H6V4zm6 0h2v12h-2V4z"
                    />
                </svg>
            </button>

            <button id="mute-btn" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
            <span id="duration" class="text-sm text-gray-600 dark:text-gray-400"
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
        <span class="text-sm text-gray-600 dark:text-gray-400">Vitesse:</span>
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

{{-- Indicateurs de débogage visuel --}}
<div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
    <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
        🔍 État du lecteur vidéo :
    </h4>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="font-medium">Script chargé :</span>
            <span id="debug-script-loaded" class="text-red-600">❌ Non</span>
        </div>
        <div>
            <span class="font-medium">Vidéo détectée :</span>
            <span id="debug-video-found" class="text-red-600">❌ Non</span>
        </div>
        <div>
            <span class="font-medium">Événements ajoutés :</span>
            <span id="debug-events-added" class="text-red-600">❌ Non</span>
        </div>
        <div>
            <span class="font-medium">Temps actuel :</span>
            <span id="debug-current-time" class="text-gray-600">0:00</span>
        </div>
        <div>
            <span class="font-medium">Durée totale :</span>
            <span id="debug-duration" class="text-gray-600">0:00</span>
        </div>
        <div>
            <span class="font-medium">Progression :</span>
            <span id="debug-progress" class="text-gray-600">0%</span>
        </div>
        <div>
            <span class="font-medium">Vidéo en lecture :</span>
            <span id="debug-playing" class="text-red-600">❌ Non</span>
        </div>
        <div>
            <span class="font-medium">Vidéo terminée :</span>
            <span id="debug-completed" class="text-red-600">❌ Non</span>
        </div>
    </div>
</div>

{{-- Actions vidéo --}}
<div class="flex justify-end items-center">
    {{-- Bouton automatique après la vidéo --}}
    <div id="auto-complete-section" class="hidden mr-4">
        <div
            class="bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg p-4 mb-4"
        >
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5 text-green-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3
                        class="text-sm font-medium text-green-800 dark:text-green-200"
                    >
                        Leçon terminée automatiquement !
                    </h3>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                        Vous serez redirigé vers la formation dans quelques
                        secondes...
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bouton manuel --}}
    <div id="manual-complete-section" class="hidden">
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
</div>

{{-- Composant Livewire pour la gestion vidéo --}}
@livewire('eleve.video-player', [ 'team' => $team, 'formation' => $formation,
'chapter' => $chapter, 'lesson' => $lesson, 'lessonContent' => $lessonContent ],
key('video-player-' . $lesson->id))
