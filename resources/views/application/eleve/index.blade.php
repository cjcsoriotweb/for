<x-application-layout :team="$team">



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace apprentis') }}
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation :navigation="[
            ['title' => 'Formations entamées', 'description' => 'Continuez une formation', 'route' => 'application.eleve.formation.index', 'icon' => 'cog'],

        ]" card="bg-red-500" :team="$team"  back="{{ route('application.index',$team) }}" />

    </x-block-div>


</x-application-layout>