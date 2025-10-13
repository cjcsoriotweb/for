<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation 
        :navigation="[
            [
                'title' => 'Configuration',
                'description' => 'Gérer les paramètres de votre application', 
                'route' => 'application.admin.configuration.index'
            ]
        ]" 
        :team="$team" 
        backTitle="Retour à l'application"
        back="{{ route('application.index', $team) }}" />




    </x-block-div>


</x-application-layout>