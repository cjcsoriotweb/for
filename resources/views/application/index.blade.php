<x-application-layout :team="$team">



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }} <b>{{ $team->name }}</b>
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation :navigation="[
            ['title' => 'Vos formations', 'button' => 'ok', 'description' => 'Gérer les paramètres de l\'application', 'route' => 'application.admin.index', 'icon' => 'cog'],
            ['title' => 'Trouver une formation', 'description' => 'Cherchez une formation', 'route' => 'application.admin.index', 'icon' => 'cog'],
            ['title' => 'Vos documents', 'description' => 'Gérer les documents de l\'application', 'route' => 'application.admin.index', 'icon' => 'cog'],

        ]" :team="$team" back="0" />

    </x-block-div>

    <x-block-div>

        <x-block-navigation :navigation="[
            ['title' => 'Application', 'description' => 'Gérer les paramètres de l\'application', 'route' => 'application.admin.index', 'icon' => 'cog'],

        ]" :team="$team" back="0" />

    </x-block-div>
</x-application-layout>