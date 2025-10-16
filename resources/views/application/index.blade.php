<x-application-layout :team="$team">



    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Accueil') }} <b>{{ $team->name }}</b>
        </h2>
    </x-slot>



    <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6">
        <h2 class="font-semibold text-xl leading-tight text-gray-900">
            {{ __('Bienvenue') }} <b>{{ $team->name }}</b>
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            {{ __('Vous êtes connecté en tant que') }} <b>{{ auth()->user()->name }}</b>
        </p>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="bg-white rounded-lg shadow p-4">
                @if(auth()->user()->hasTeamRole($team,'admin'))
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Administrateur
                </h3>
                <p class="text-gray-500 mb-4">
                    Gérer les paramètres de l'application
                </p>
                <a href="{{ route('application.admin.index', $team) }}" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 hover:scale-105">
                    Tableau de bord
                </a>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                @if(auth()->user()->hasTeamRole($team,'manager'))
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Organisme
                </h3>
                <p class="text-gray-500 mb-4">
                    Acceder aux suivis des apprentis
                </p>
                <a href="{{ route('application.eleve.index', $team) }}" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 hover:scale-105">
                    Suivis
                </a>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                @if(auth()->user()->hasTeamRole($team,'eleve'))
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Apprentis
                </h3>
                <p class="text-gray-500 mb-4">
                    Gérer l'espace des apprentis
                </p>
                <a href="{{ route('application.eleve.index', $team) }}" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition-all duration-200 hover:scale-105">
                    Espace apprentis
                </a>
                @endif
            </div>

        </div>
    </div>
</x-application-layout>