<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">admin_panel_settings</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Tableau de bord Administrateur</h2>
                <p class="text-blue-100 text-sm">Gérez votre plateforme de formation</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Bienvenue sur votre panneau d'administration</h1>
                    <p class="text-blue-100 text-lg mb-6">Gérez efficacement votre plateforme de formations et vos utilisateurs.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">settings</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats rapide -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{route('application.admin.users.index', $team)}}" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">people</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{count($usersInTeam)}}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Utilisateurs dans cette application</div>
                    </div>
                </div>
            </a>

            <a href="{{route('application.admin.formations.index', $team)}}" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">school</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{count($formationsByTeam)}}/{{count($formationsAll)}}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Formations disponibles</div>
                    </div>
                </div>
            </a>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">analytics</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">0</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Inscriptions ce mois</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section actions rapides -->
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:bg-opacity-20 dark:to-teal-900 dark:bg-opacity-20 rounded-2xl p-8 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">bolt</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-200 mb-2">{{__('Actions rapides')}}</h3>
                </div>
                <div class="hidden md:block">
                    <div class="flex space-x-3">
                        <a href="{{ route('application.admin.configuration.index', $team) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                            <span class="material-symbols-outlined mr-2 text-sm">settings</span>
                            Configuration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-application-layout>
