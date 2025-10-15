<x-application-layout :team="$team">



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }} <b>{{ $team->name }}</b>
        </h2>
    </x-slot>



    <x-block-div>

        <x-block-navigation :navigation="[
            ['title' => 'Tableau de bord', 'description' => 'Gérer les paramètres de l\'application', 'route' => 'application.admin.index', 'icon' => 'cog'],

        ]" back="0" :team="$team" />

        <x-block-navigation :navigation="[
            ['title' => 'Suivis', 'description' => 'Acceder aux suivis des apprentis', 'route' => 'application.eleve.index', 'icon' => 'cog'],

        ]" back="0" :team="$team" />

        <x-block-navigation :navigation="[
            ['title' => 'Espace Apprentis', 'description' => 'Gérer l\'espace des apprentis', 'route' => 'application.eleve.index', 'icon' => 'cog'],
            ['title' => 'Suivis', 'description' => 'Acceder aux suivis des apprentis', 'route' => 'application.eleve.index', 'icon' => 'cog'],

        ]" back="0" :team="$team" />

    </x-block-div>
</x-application-layout>