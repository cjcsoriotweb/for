<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation 
        :navigation="[
            [
                'title' => 'Configuration',
                'description' => 'Gérer les paramètres de votre application', 
                'route' => 'application.admin.configuration.index',
            ],
            [
                'title' => 'Utilisateurs',
                'description' => 'Gérer les utilisateurs de votre application', 
                'route' => 'application.admin.users.index',
            ],
            [
                'title' => 'Formations',
                'description' => 'Gérer les formations de votre application', 
                'route' => 'application.admin.formations.index',
            ]
        ]" 
        :team="$team" 
        backTitle="Retour à l'application"
        back="{{ route('application.index', $team) }}" />




    </x-block-div>


</x-application-layout>