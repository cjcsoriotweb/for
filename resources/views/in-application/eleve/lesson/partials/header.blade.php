{{-- En-tête de la leçon avec design moderne --}}
<div class="bg-gradient-to-br from-white via-indigo-50/30 to-purple-50/30 dark:from-gray-800 dark:via-indigo-900/10 dark:to-purple-900/10 overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-gray-200/50 dark:border-gray-700/50">
    {{-- Header avec gradient --}}
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full p-3">
                    @if($lessonType === 'video')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    @elseif($lessonType === 'quiz')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">
                        {{ $lesson->getName() }}
                    </h1>
                    <div class="flex items-center space-x-4 text-white text-sm opacity-90">
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>{{ $chapter->title }}</span>
                        </span>
                        @if($lessonContent && $lessonContent->estimated_read_time)
                            <span class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $lessonContent->estimated_read_time }} min</span>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Badge de statut --}}
            <div class="text-right" wire:poll.2s>
                @if($lessonProgress)
                    @if($lessonProgress->pivot->status === 'completed')
                        <div class="bg-green-500 bg-opacity-20 backdrop-blur-sm border border-green-400 border-opacity-30 rounded-full px-4 py-2">
                            <div class="flex items-center space-x-2 text-green-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Terminée</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-500 bg-opacity-20 backdrop-blur-sm border border-blue-400 border-opacity-30 rounded-full px-4 py-2">
                            <div class="flex items-center space-x-2 text-blue-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="font-semibold">En cours</span>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="bg-gray-500 bg-opacity-20 backdrop-blur-sm border border-gray-400 border-opacity-30 rounded-full px-4 py-2">
                        <div class="flex items-center space-x-2 text-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Non commencée</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        @if($lessonContent && $lessonContent->description)
            <div class="mt-4 text-white text-opacity-90 text-lg leading-relaxed">
                {{ $lessonContent->description }}
            </div>
        @endif
    </div>

    {{-- Section statistiques --}}
    @if($lessonProgress)
        <div class="px-8 py-6 bg-white dark:bg-gray-800">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if($lessonType === 'text' && $lessonProgress->pivot->read_percent !== null)
                    @livewire('eleve.formation.progress-display-header', ['lesson' => $lesson], key('progress-header-' . $lesson->id))
                @endif

                @if($lessonType === 'quiz' && $lessonProgress->pivot->attempts > 0)
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-5 rounded-xl border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm font-semibold text-purple-700 dark:text-purple-300">Tentatives</div>
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $lessonProgress->pivot->attempts }}</div>
                        <div class="text-sm text-purple-600 dark:text-purple-400 mt-1">Meilleur score: {{ $lessonProgress->pivot->best_score ?? 0 }}%</div>
                    </div>
                @endif

                @if($lessonProgress->pivot->started_at)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-5 rounded-xl border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-sm font-semibold text-green-700 dark:text-green-300">Dernière activité</div>
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-lg font-bold text-green-900 dark:text-green-100">
                            {{ \Carbon\Carbon::parse($lessonProgress->pivot->last_activity_at)->diffForHumans() }}
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 mt-1">
                            Commencé le {{ \Carbon\Carbon::parse($lessonProgress->pivot->started_at)->format('d/m/Y') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
