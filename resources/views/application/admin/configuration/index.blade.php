<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">settings</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Configuration</h2>
                <p class="text-blue-100 text-sm">Personnaliser votre plateforme</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Configuration de l'application</h1>
                    <p class="text-blue-100 text-lg mb-6">Personnalisez l'apparence et les paramètres de votre plateforme.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">tune</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Change name -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">edit</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-slate-900 dark:text-white mb-1">Changer le nom</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Remplacer le nom {{ $team->name }}</p>
                        <a href="{{ route('application.admin.configuration.name', $team) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
                            Modifier le nom
                        </a>
                    </div>
                </div>
            </div>

            <!-- Change logo -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">image</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-slate-900 dark:text-white mb-1">Changer le logo</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Mettre à jour l'image de votre plateforme</p>
                        @if($team->profile_photo_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/'.$team->profile_photo_path) }}" alt="Logo actuel" class="w-16 h-16 rounded-lg object-cover">
                            </div>
                        @endif
                        <a href="{{ route('application.admin.configuration.logo', $team) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg text-sm transition-colors">
                            Modifier le logo
                        </a>
                    </div>
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
