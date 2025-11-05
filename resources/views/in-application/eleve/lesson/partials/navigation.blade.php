{{-- Navigation de la leçon avec design moderne --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-gray-700">
    {{-- Header de navigation --}}
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center space-x-2">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                <span>Navigation</span>
            </h3>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Leçon {{ $lesson->position }} sur {{ $chapter->lessons()->count() }}
            </div>
        </div>
    </div>

    <div class="p-6">
        {{-- Navigation entre leçons --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" wire:poll.2s>
            {{-- Leçon précédente --}}
            <div class="text-center">
                @if($previousLesson)
                    <a
                        href="{{ route('eleve.lesson.show', [$team, $formation, $chapter, $previousLesson]) }}"
                        class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 dark:from-gray-700 dark:to-gray-600 dark:hover:from-gray-600 dark:hover:to-gray-500 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 group"
                    >
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <div class="text-left">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Précédente</div>
                            <div class="text-sm font-medium truncate max-w-32">{{ $previousLesson->title }}</div>
                        </div>
                    </a>
                @else
                    <div class="inline-flex items-center justify-center w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-xl cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <div class="text-left">
                            <div class="text-xs">Précédente</div>
                            <div class="text-sm">Aucune</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Retour à la formation --}}
            <div class="text-center">
                <a
                    href="{{ route('eleve.formation.show', [$team, $formation]) }}"
                    class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl group"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <div class="text-center">
                        <div class="text-xs opacity-90">Retour à la</div>
                        <div class="text-sm font-medium">Formation</div>
                    </div>
                </a>
            </div>

            {{-- Leçon suivante --}}
            <div class="text-center">
                @if($nextLesson)
                    @php
                        $isLessonCompleted = optional(optional($lessonProgress ?? null)->pivot)->status === 'completed';
                    @endphp

                    @if($isLessonCompleted)
                        {{-- Leçon terminée - accès autorisé --}}
                        <a
                            href="{{ route('eleve.lesson.show', [$team, $formation, $chapter, $nextLesson]) }}"
                            class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl group"
                        >
                            <div class="text-right mr-2">
                                <div class="text-xs opacity-90">Suivante</div>
                                <div class="text-sm font-medium truncate max-w-32">{{ $nextLesson->title }}</div>
                            </div>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        {{-- Leçon en cours - accès bloqué avec animation de chargement --}}
                        <div class="relative">
                            <div class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 text-gray-500 dark:text-gray-400 rounded-xl cursor-not-allowed group">
                                <div class="text-right mr-2">
                                    <div class="text-xs">Suivante</div>
                                    <div class="text-sm font-medium truncate max-w-32">{{ $nextLesson->title }}</div>
                                </div>
                                {{-- Animation de chargement --}}
                                <div class="relative w-5 h-5">
                                    <div class="absolute inset-0 border-2 border-gray-400 dark:border-gray-500 border-t-transparent rounded-full animate-spin"></div>
                                    <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>

                            {{-- Tooltip explicatif --}}
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                                Terminez cette leçon pour accéder à la suivante
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800 dark:border-t-gray-700"></div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="inline-flex items-center justify-center w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-xl cursor-not-allowed">
                        <div class="text-right mr-2">
                            <div class="text-xs">Suivante</div>
                            <div class="text-sm">Aucune</div>
                        </div>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Message d'information si la leçon est déjà terminée --}}
        @if($lessonProgress && $lessonProgress->pivot->status === 'completed')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 dark:bg-green-800 rounded-full p-2">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-green-800 dark:text-green-200">Leçon terminée</h4>
                        <p class="text-sm text-green-700 dark:text-green-300">Cette leçon est terminée. Vous ne pouvez plus la modifier.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Chapitres alternatifs si disponibles --}}
        @if($otherChapters->isNotEmpty())
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Autres chapitres</span>
                </h4>
                <div class="space-y-2">
                    @foreach($otherChapters as $otherChapter)
                        <a
                            href="{{ route('eleve.formation.show', [$team, $formation]) }}#chapter-{{ $otherChapter->id }}"
                            class="block p-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors duration-200"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $otherChapter->title }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $otherChapter->lessons()->count() }} leçons</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
