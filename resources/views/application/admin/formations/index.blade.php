<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">school</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Gestion des formations</h2>
                <p class="text-blue-100 text-sm">Administrer les formations de votre plateforme</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Gestion des formations</h1>
                    <p class="text-blue-100 text-lg mb-6">Gérez les formations disponibles sur votre plateforme.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">library_books</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:bg-opacity-20 dark:to-teal-900 dark:bg-opacity-20 rounded-2xl p-8 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">settings</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-200 mb-2">Gérer les formations</h3>
                    <p class="text-emerald-700 dark:text-emerald-300">
                        Activez ou désactivez les formations disponibles pour votre équipe.
                    </p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('application.admin.formations.list', $team) }}"
                        class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                        <span class="material-symbols-outlined mr-2">arrow_forward</span>
                        Gérer les formations
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation retour -->
        <div class="mt-8 text-center">
            <a href="{{ route('application.admin.index', $team) }}"
                class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                Retour à l'administration
            </a>
        </div>
    </div>
</x-application-layout>
