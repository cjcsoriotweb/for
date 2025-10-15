<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">school</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Espace apprentissage</h2>
                <p class="text-blue-100 text-sm">Développez vos compétences avec nos formations</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Bienvenue sur votre espace d'apprentissage</h1>
                    <p class="text-blue-100 text-lg mb-6">Découvrez et suivez des formations adaptées à vos besoins professionnels.</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-3 py-1 bg-blue-500 bg-opacity-30 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">workspace_premium</span>
                            Formation certifiante
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-green-500 bg-opacity-30 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">schedule</span>
                            Rythme flexible
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-purple-500 bg-opacity-30 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            Apprentissage collaboratif
                        </span>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">rocket_launch</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats rapide -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">play_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Formations en cours</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Formations terminées</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">trophy</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Certifications obtenues</div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Section motivation -->
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:bg-opacity-20 dark:to-teal-900 dark:bg-opacity-20 rounded-2xl p-8 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">celebration</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-200 mb-2">Prêt à apprendre ?</h3>
                    <p class="text-emerald-700 dark:text-emerald-300">
                        Choisissez une formation et commencez votre parcours d'apprentissage. Chaque étape vous rapproche de vos objectifs professionnels.
                    </p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('application.eleve.formations.list', $team) }}"
                        class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                        <span class="material-symbols-outlined mr-2">arrow_forward</span>
                        Commencer
                    </a>
                </div>
            </div>
        </div>
    </div>

            <!-- Actions principales -->
        <div class="">
            <x-block-navigation :navigation="[
                ['title' => 'Parcourir les formations', 'description' => 'Découvrez notre catalogue complet de formations', 'route' => 'application.eleve.formations.list', 'icon' => 'library_books', 'color' => 'bg-blue-500'],
                 ['title' => 'Mes formations', 'description' => 'Continuez vos formations en cours', 'route' => 'application.eleve.formations.list', 'icon' => 'school', 'color' => 'bg-green-500']
            ]" card="bg-white dark:bg-slate-800" :team="$team" back="0" />

        </div>



</x-application-layout>
