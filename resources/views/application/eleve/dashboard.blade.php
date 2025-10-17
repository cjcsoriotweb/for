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
        <!-- Stats rapide -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{route('application.eleve.formations.list', $team)}}" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">play_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Formations en cours</div>
                    </div>
                </div>
            </a>

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



    </div>
</x-application-layout>
