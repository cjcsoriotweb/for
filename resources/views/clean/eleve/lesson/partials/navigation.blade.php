{{-- Navigation de la leçon --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Navigation</h3>
        <div class="flex justify-between items-center">
            <div>
                @if($previousLesson) @php $prevLessonType =
                $previousLesson->lessonable_type === \App\Models\Quiz::class ?
                'Quiz' : ($previousLesson->lessonable_type ===
                \App\Models\VideoContent::class ? 'Vidéo' : 'Contenu'); @endphp
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
                    ← {{ $prevLessonType }} précédente
                </a>
                @else
                <span class="text-gray-400">Première leçon</span>
                @endif
            </div>

            <div class="text-center">
                <a
                    href="{{
                        route('eleve.formation.show', [$team, $formation])
                    }}"
                    class="text-blue-600 hover:text-blue-800"
                >
                    Retour à la formation
                </a>
            </div>

            <div>
                @if($nextLesson && $lessonProgress &&
                $lessonProgress->pivot->status === 'completed') @php
                $nextLessonType = $nextLesson->lessonable_type ===
                \App\Models\Quiz::class ? 'Quiz' : ($nextLesson->lessonable_type
                === \App\Models\VideoContent::class ? 'Vidéo' : 'Contenu');
                @endphp
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
                    {{ $nextLessonType }} suivante →
                </a>
                @elseif($nextLesson && (!$lessonProgress ||
                $lessonProgress->pivot->status !== 'completed'))
                <span
                    class="text-gray-400"
                    title="Terminez d'abord la leçon actuelle"
                >
                    Leçon suivante (non disponible)
                </span>
                @elseif($otherChapters->count() > 0) @php $nextChapter =
                $otherChapters->first(); $firstLesson =
                $nextChapter->lessons->first(); @endphp @if($firstLesson) @php
                $firstLessonType = $firstLesson->lessonable_type ===
                \App\Models\Quiz::class ? 'Quiz' :
                ($firstLesson->lessonable_type ===
                \App\Models\VideoContent::class ? 'Vidéo' : 'Contenu'); @endphp
                <a
                    href="{{
                        route('eleve.lesson.show', [
                            $team,
                            $formation,
                            $nextChapter,
                            $firstLesson
                        ])
                    }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                >
                    {{ $firstLessonType }} du chapitre suivant →
                </a>
                @else
                <span class="text-gray-400"
                    >Chapitre suivant (pas de leçons)</span
                >
                @endif @else
                <span class="text-gray-400">Dernière leçon</span>
                @endif
            </div>
        </div>

        {{-- Message d'information si la leçon est déjà terminée --}}
        @if($lessonProgress && $lessonProgress->pivot->status === 'completed')
        <div
            class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
        >
            <p class="text-green-800 dark:text-green-200 text-sm">
                ✓ Cette leçon est terminée. Vous ne pouvez plus la modifier.
            </p>
        </div>
        @endif
    </div>
</div>
