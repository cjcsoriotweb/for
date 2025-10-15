<x-application-layout :team="$team">




    <x-block-div>

        <x-block-navigation :navigation="[
            ['title' => 'Formations entamées', 'description' => 'Continuez une formation', 'route' => 'application.eleve.formations.list', 'icon' => 'cog'],

        ]" card="bg-red-500" :team="$team"  back="{{ route('application.index',$team) }}" />

    </x-block-div>


</x-application-layout>