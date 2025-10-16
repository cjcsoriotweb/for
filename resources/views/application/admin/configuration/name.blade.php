<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">edit</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Modifier le nom</h2>
                <p class="text-blue-100 text-sm">Changer le nom de votre plateforme</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Modification du nom</h1>
                    <p class="text-blue-100 text-lg mb-6">Modifiez le nom d'affichage de votre plateforme.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">text_fields</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            @livewire('teams.update-team-name-form', ['team' => $team])
        </div>

        <!-- Navigation retour -->
        <div class="mt-8 text-center">
            <a href="{{ route('application.admin.configuration.index', $team) }}"
                class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                Retour à la configuration
            </a>
        </div>
    </div>
</x-application-layout>
