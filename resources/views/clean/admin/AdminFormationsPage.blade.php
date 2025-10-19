<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center"
            >
                <span class="material-symbols-outlined text-white text-xl"
                    >admin_panel_settings</span
                >
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    Tableau de bord Administrateur
                </h2>
                <p class="text-blue-100 text-sm">
                    Gérez votre plateforme de formation
                </p>
            </div>
        </div>
    </x-slot>

    <x-admin.AdminFormations :team="$team" />
    @include('clean.admin.partials.home-button', ['team' => $team])
</x-application-layout>
