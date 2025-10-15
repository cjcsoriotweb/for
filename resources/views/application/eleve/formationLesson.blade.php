<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-violet-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">play_circle</span>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-xl text-white leading-tight">{{ $lesson->title }}</h2>
                <p class="text-blue-100 text-sm">{{ $formation->title }} - {{ $chapter->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('application.eleve.formations.continue', [$team, $formation]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Retour aux leçons
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Contenu de la leçon -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            @if($videoContent)
                <!-- Contenu vidéo -->
                <div class="aspect-video bg-black rounded-t-xl overflow-hidden">
                    @if($videoContent->video_url)
                        <video controls class="w-full h-full" poster="{{ $videoContent->thumbnail_url ?? '' }}">
                            <source src="{{ $videoContent->video_url }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <div class="text-center text-white">
                                <span class="material-symbols-outlined text-6xl mb-4">video_camera_front_off</span>
                                <p>Vidéo non disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">{{ $lesson->title }}</h3>
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        {!! $videoContent->description !!}
                    </div>

                    @if($videoContent->transcript)
                        <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                            <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Transcription</h4>
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                {!! $videoContent->transcript !!}
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($textContent)
                <!-- Contenu texte -->
                <div class="p-8">
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-6">{{ $lesson->title }}</h3>
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        {!! $textContent->content !!}
                    </div>

                    @if($textContent->summary)
                        <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">lightbulb</span>
                                </div>
                                <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Points clés</h4>
                            </div>
                            <div class="text-blue-700 dark:text-blue-300">
                                {!! $textContent->summary !!}
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Aucun contenu -->
                <div class="p-16 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-3xl text-slate-400">description</span>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Contenu non disponible</h3>
                    <p class="text-slate-600 dark:text-slate-400">Le contenu de cette leçon sera bientôt ajouté.</p>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <div>
                @php
                    $prevLesson = App\Models\Lesson::where('chapter_id', $chapter->id)
                        ->where('position', '<', $lesson->position)
                        ->orderBy('position', 'desc')
                        ->first();

                    if (!$prevLesson) {
                        $prevChapter = App\Models\Chapter::where('formation_id', $formation->id)
                            ->where('position', '<', $chapter->position)
                            ->orderBy('position', 'desc')
                            ->first();
                        if ($prevChapter) {
                            $prevLesson = $prevChapter->lessons()->orderBy('position', 'desc')->first();
                        }
                    }
                @endphp

                @if($prevLesson)
                    <a href="{{ route('application.eleve.formations.lesson', [$team, $formation, $prevLesson->chapter, $prevLesson]) }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg transition-colors">
                        <span class="material-symbols-outlined mr-2">chevron_left</span>
                        Leçon précédente
                    </a>
                @endif
            </div>

            <div class="flex items-center space-x-3">
                @php
                    $nextLesson = App\Models\Lesson::where('chapter_id', $chapter->id)
                        ->where('position', '>', $lesson->position)
                        ->orderBy('position')
                        ->first();

                    if (!$nextLesson) {
                        $nextChapter = App\Models\Chapter::where('formation_id', $formation->id)
                            ->where('position', '>', $chapter->position)
                            ->orderBy('position')
                            ->first();
                        if ($nextChapter) {
                            $nextLesson = $nextChapter->lessons()->orderBy('position')->first();
                        }
                    }
                @endphp

                @if($nextLesson)
                    <form method="POST" action="{{ route('application.eleve.formations.lesson.complete', [$team, $formation, $chapter, $lesson]) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            Marquer comme terminée
                        </button>
                    </form>

                    <a href="{{ route('application.eleve.formations.lesson', [$team, $formation, $nextLesson->chapter, $nextLesson]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Leçon suivante
                        <span class="material-symbols-outlined ml-2">chevron_right</span>
                    </a>
                @else
                    <form method="POST" action="{{ route('application.eleve.formations.lesson.complete', [$team, $formation, $chapter, $lesson]) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                            <span class="material-symbols-outlined mr-2">check_circle</span>
                            Terminer la leçon
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Ressources complémentaires -->
        @if(isset($lesson->resources) && $lesson->resources)
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Ressources complémentaires</h4>
                <div class="flex flex-wrap gap-3">
                    @foreach($lesson->resources as $resource)
                        <a href="{{ $resource['url'] }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 transition-colors">
                            <span class="material-symbols-outlined mr-2">{{ $resource['icon'] ?? 'link' }}</span>
                            {{ $resource['title'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-application-layout>
