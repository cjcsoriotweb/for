<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        
        @include('teams.partials.update-team-photo-form', ['team' => $team])
        

    
    </x-block-div>

    <x-block-div>
        
        <x-block-navigation :navigation="[
            [
                'title' => 'Changer nom',
                'description' => '...',
                'route' => 'application.admin.configuration.name'
            ],
            [
                'title' => 'Changer logo',
                'description' => '...',
                'route' => 'application.admin.configuration.logo'
            ]
        ]" 
        :team="$team" 
        backTitle="Retour à la configuration"
        back="{{ route('application.admin.configuration.index', $team) }}" />

    </x-block-div>


</x-application-layout>