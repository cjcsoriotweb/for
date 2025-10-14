<x-application-layout :team="$team">



    <x-slot name="block">
        <p>Page d'accueil d'accueil.</p>
    </x-slot>


    <x-block-div>

        <x-block-navigation :navigation="[
            ['hasTeamRole' => 'admin', 'title' => 'Administration', 'description' => 'Gérer les paramètres de l\'application', 'route' => 'application.admin.index', 'icon' => 'cog'],

        ]" :team="$team" back="0" />

    </x-block-div>
</x-application-layout>