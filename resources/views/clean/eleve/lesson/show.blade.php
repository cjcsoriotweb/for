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
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a
                        href="{{ route('eleve.index', $team) }}"
                        class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                    >
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <a
                            href="{{
                                route('eleve.formation.show', [
                                    $team,
                                    $formation
                                ])
                            }}"
                            class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                        >
                            {{ $formation->title }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <span
                            class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400"
                            >{{ $chapter->title }}</span
                        >
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <span
                            class="ml-1 text-blue-600 md:ml-2 dark:text-blue-400"
                            >{{ $lesson->title }}</span
                        >
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Lesson Header -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
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

                <!-- Lesson Progress -->
                @if($lessonProgress)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    @if($lessonType === 'text' &&
                    $lessonProgress->pivot->read_percent !== null)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Progression de lecture
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
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
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $lessonProgress->pivot->attempts }}
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Meilleur score
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
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

        <!-- Lesson Content -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if($lessonType === 'video')
                <!-- Video Content -->
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
                            Votre navigateur ne supporte pas la lecture de
                            vidéos.
                        </video>
                    </div>
                    @endif @if($lessonContent->duration_minutes)
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Durée: {{ $lessonContent->duration_minutes }} minutes
                    </div>
                    @endif
                </div>

                <!-- Video Actions -->
                <div class="flex justify-between items-center">
                    <button
                        onclick="startLesson()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Commencer la leçon
                    </button>
                    <button
                        onclick="completeLesson()"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Marquer comme terminée
                    </button>
                </div>

                @elseif($lessonType === 'text')
                <!-- Text Content -->
                <div class="prose dark:prose-invert max-w-none mb-6">
                    {!! nl2br(e($lessonContent->content)) !!}
                </div>

                <!-- Reading Progress -->
                <div class="mb-6">
                    <div
                        class="flex justify-between text-sm text-gray-600 mb-2"
                    >
                        <span>Progression de lecture</span>
                        <span id="read-progress-text">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                            id="read-progress-bar"
                            style="width: {{ $lessonProgress->pivot->read_percent ?? 0 }}%"
                        ></div>
                    </div>
                </div>

                <!-- Text Actions -->
                <div class="flex justify-between items-center">
                    <button
                        onclick="startLesson()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Commencer la lecture
                    </button>
                    <div class="flex space-x-2">
                        @if($lessonContent->allow_download)
                        <button
                            onclick="downloadContent()"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Télécharger
                        </button>
                        @endif
                        <button
                            onclick="completeLesson()"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Marquer comme lue
                        </button>
                    </div>
                </div>

                @elseif($lessonType === 'quiz')
                <!-- Quiz Content -->
                <div class="mb-6">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4"
                    >
                        <h3
                            class="font-semibold text-blue-900 dark:text-blue-100 mb-2"
                        >
                            Informations du Quiz
                        </h3>
                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm"
                        >
                            <div>
                                <span class="font-medium"
                                    >Score de passage:</span
                                >
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
                    $lessonContent->max_attempts && $lessonContent->max_attempts
                    > 0)
                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4"
                    >
                        <p class="text-red-800 dark:text-red-200">
                            Vous avez atteint le nombre maximum de tentatives
                            pour ce quiz.
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

        <!-- Lesson Navigation -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-semibold mb-4">Navigation</h3>
                <div class="flex justify-between items-center">
                    <div>
                        @if($previousLesson)
                        <a
                            href="{{
                                route('eleve.lesson.show', [
                                    $team,
                                    $formation,
                                    $chapter,
                                    $previousLesson
                                ])
                            }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                        >
                            ← Leçon précédente
                        </a>
                        @else
                        <span class="text-gray-400">Première leçon</span>
                        @endif
                    </div>

                    <div class="text-center">
                        <a
                            href="{{
                                route('eleve.formation.show', [
                                    $team,
                                    $formation
                                ])
                            }}"
                            class="text-blue-600 hover:text-blue-800"
                        >
                            Retour à la formation
                        </a>
                    </div>

                    <div>
                        @if($nextLesson)
                        <a
                            href="{{
                                route('eleve.lesson.show', [
                                    $team,
                                    $formation,
                                    $chapter,
                                    $nextLesson
                                ])
                            }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Leçon suivante →
                        </a>
                        @elseif($otherChapters->count() > 0)
                        <a
                            href="{{ route('eleve.lesson.show', [$team, $formation, $otherChapters->first(), $otherChapters->first()->lessons->first()]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Chapitre suivant →
                        </a>
                        @else
                        <span class="text-gray-400">Dernière leçon</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lesson data passed from server
        window.lessonData = @json([
            'type' => $lessonType,
            'hasProgress' => $lessonProgress ? true : false,
            'currentProgress' => $lessonProgress ? ($lessonProgress->pivot->read_percent ?? 0) : 0,
            'lessonTitle' => addslashes($lesson->title),
            'content' => $lessonContent ? addslashes($lessonContent->content) : '',
            'routes' => [
                'start' => route('eleve.lesson.start', [$team, $formation, $chapter, $lesson]),
                'complete' => route('eleve.lesson.complete', [$team, $formation, $chapter, $lesson]),
                'progress' => route('eleve.lesson.progress', [$team, $formation, $chapter, $lesson])
            ]
        ]);

        // Lesson tracking functions
        function startLesson() {
            fetch(window.lessonData.routes.start, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Leçon démarrée');
                }
            });
        }

        function completeLesson() {
            fetch(window.lessonData.routes.complete, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        // Initialize lesson tracking based on type
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lessonData.type === 'text') {
                // Reading progress tracking for text content
                let readProgress = window.lessonData.currentProgress;

                const content = document.querySelector('.prose');
                if (content) {
                    content.addEventListener('scroll', function() {
                        const scrollTop = content.scrollTop;
                        const scrollHeight = content.scrollHeight - content.clientHeight;
                        const progress = Math.min((scrollTop / scrollHeight) * 100, 100);

                        readProgress = Math.max(readProgress, progress);

                        // Update progress bar
                        document.getElementById('read-progress-bar').style.width = readProgress + '%';
                        document.getElementById('read-progress-text').textContent = Math.round(readProgress) + '%';

                        // Send progress to server every 5 seconds
                        clearTimeout(window.progressTimeout);
                        window.progressTimeout = setTimeout(() => {
                            fetch(window.lessonData.routes.progress, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    read_percent: Math.round(readProgress)
                                })
                            });
                        }, 5000);
                    });
                }
            }

            if (window.lessonData.type === 'video') {
                // Video progress tracking
                const video = document.getElementById('lesson-video');
                if (video) {
                    video.addEventListener('play', startLesson);
                    video.addEventListener('ended', completeLesson);
                }
            }
        });

        function downloadContent() {
            // Create a temporary link to download the content
            const content = window.lessonData.content;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = window.lessonData.lessonTitle + '.txt';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }
    </script>
</x-eleve-layout>
