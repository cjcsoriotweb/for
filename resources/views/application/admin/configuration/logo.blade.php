<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        
        @include('teams.partials.update-team-photo-form', ['team' => $team])
        

    
    </x-block-div>

    <x-block-div>
        
        <x-block-navigation :navigation="[]" 
        :team="$team" 
        backTitle="Retour à la configuration"
        back="{{ route('application.admin.configuration.index', $team) }}" />

    </x-block-div>


</x-application-layout>