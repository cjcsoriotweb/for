<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion Utilisateurs') }}
        </h2>
    </x-slot>


    <x-block-div>
        <x-block-navigation :navigation="[
                    [
                        'title' => 'Gestions utilisateurs',
                        'description' => 'Remplacer le nom ',
                        'route' => 'application.admin.configuration.name',
                    ],
                ]" :team="$team" backTitle="Retour à Administration"
                back="{{ route('application.admin.index', $team) }}" />

        </x-block-div>
    </x-block-div>


</x-application-layout>
