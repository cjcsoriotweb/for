{{-- Navigation de la leçon --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
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
                        route('eleve.formation.show', [$team, $formation])
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
                @elseif($otherChapters->count() > 0) @php $nextChapter =
                $otherChapters->first(); $firstLesson =
                $nextChapter->lessons->first(); @endphp @if($firstLesson)
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
                    Chapitre suivant →
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
    </div>
</div>
