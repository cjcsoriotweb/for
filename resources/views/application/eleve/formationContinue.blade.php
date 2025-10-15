<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">play_circle</span>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-xl text-white leading-tight">{{ $formation->title }}</h2>
                <p class="text-violet-100 text-sm">Continuez votre apprentissage</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Barre de progression -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-violet-600 dark:text-violet-400">trending_up</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Votre progression</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                                <div class="bg-violet-600 h-3 rounded-full transition-all duration-500"
                                    style="width: {{ $formationUser ? $formationUser->progress_percent : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
                            {{ $formationUser ? $formationUser->progress_percent : 0 }}% terminé
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">3</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Leçons terminées</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">schedule</span>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">2h 15m</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Temps passé</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl text-purple-600 dark:text-purple-400">local_fire_department</span>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">5</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Jours consécutifs</div>
                </div>
            </div>
        </div>

        <!-- Contenu de la formation -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="border-b border-slate-200 dark:border-slate-700 px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">menu_book</span>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Votre formation continue</h2>
                </div>
            </div>

            <div class="p-8">
                <div class="flex items-start space-x-6 mb-8">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400">school</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">{{ $formation->title }}</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed mb-6">{{ $formation->description }}</p>

                        <div class="grid md:grid-cols-2 gap-6">
                            @if($formationUser && $formationUser->current_lesson_id)
                                <div class="bg-violet-50 dark:bg-violet-900/20 rounded-xl p-6 border border-violet-200 dark:border-violet-800">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-violet-600 dark:text-violet-400">next_plan</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-violet-800 dark:text-violet-200">Prochaine leçon</div>
                                            <div class="font-semibold text-violet-900 dark:text-violet-100">{{ \App\Models\Lesson::find($formationUser->current_lesson_id)?->title ?? 'Leçon suivante' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-violet-50 dark:bg-violet-900/20 rounded-xl p-6 border border-violet-200 dark:border-violet-800">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                                            <span class="material-symbols-outlined text-violet-600 dark:text-violet-400">play_arrow</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-violet-800 dark:text-violet-200">Démarrer</div>
                                            <div class="font-semibold text-violet-900 dark:text-violet-100">Première leçon</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-6 border border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">celebration</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Objectif</div>
                                        <div class="font-semibold text-emerald-900 dark:text-emerald-100">{{ $formation->progressTarget() }}% de progression</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des chapitres et leçons -->
                <div class="space-y-6">
                    @forelse($formation->chapters as $chapter)
                        <div class="border border-slate-200 dark:border-slate-600 rounded-lg overflow-hidden">
                            <div class="bg-slate-50 dark:bg-slate-700 px-6 py-4 border-b border-slate-200 dark:border-slate-600">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ $loop->iteration }}</span>
                                    </div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $chapter->title }}</h3>
                                </div>
                            </div>

                            <div class="divide-y divide-slate-200 dark:divide-slate-600">
                                @forelse($chapter->lessons as $lesson)
                                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                        <div class="flex items-center space-x-4">
                                            <!-- Status de la leçon -->
                                            @php
                                                $lessonProgress = $lesson->learners()->where('users.id', auth()->id())->first()?->pivot;
                                            @endphp

                                            @if($lessonProgress && $lessonProgress->status === 'completed')
                                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-sm text-green-600 dark:text-green-400">check_circle</span>
                                                </div>
                                            @elseif($lessonProgress && $lessonProgress->status === 'in_progress')
                                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-sm text-blue-600 dark:text-blue-400">schedule</span>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-slate-100 dark:bg-slate-600 rounded-full flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-sm text-slate-400 dark:text-slate-500">radio_button_unchecked</span>
                                                </div>
                                            @endif

                                            <div>
                                                <h4 class="font-medium text-slate-900 dark:text-white">{{ $lesson->title }}</h4>
                                                @if($lesson->description)
                                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $lesson->description }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            @if($lessonProgress && $lessonProgress->status === 'completed')
                                                <span class="text-sm text-green-600 dark:text-green-400 font-medium">Terminée</span>
                                            @elseif($lessonProgress && $lessonProgress->last_activity_at)
                                                <span class="text-sm text-slate-500 dark:text-slate-400">En cours</span>
                                            @endif

                                            <a href="{{ route('application.eleve.formations.lesson', [$team, $formation, $chapter, $lesson]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition-colors">
                                                @if($lessonProgress && $lessonProgress->status === 'completed')
                                                    <span class="material-symbols-outlined text-sm mr-2">replay</span>
                                                    Revoir
                                                @else
                                                    <span class="material-symbols-outlined text-sm mr-2">play_arrow</span>
                                                    Commencer
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        Aucune leçon dans ce chapitre
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-outlined text-3xl text-slate-400">menu_book</span>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Aucun chapitre disponible</h3>
                            <p class="text-slate-600 dark:text-slate-400">Les chapitres seront bientôt ajoutés à cette formation.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section motivation -->
        <div class="bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-2xl p-8 border border-violet-200 dark:border-violet-800">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-violet-600 dark:text-violet-400">local_fire_department</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-violet-800 dark:text-violet-200 mb-2">Restez motivé !</h3>
                    <p class="text-violet-700 dark:text-violet-300">
                        Vous êtes sur la bonne voie ! Continuez votre apprentissage à votre rythme et atteignez vos objectifs professionnels.
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center space-x-2 text-violet-600 dark:text-violet-400">
                        <span class="material-symbols-outlined">local_fire_department</span>
                        <span class="text-sm font-medium">5 jours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-application-layout>
