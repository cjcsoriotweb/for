<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        
        <div>
            <p>Nombre de formations activé : {{ $formations->count() }}</p>
        </div>

    </x-block-div>

    <x-block-div>
        <x-block-navigation 
        :navigation="[
           
        ]" 
        :team="$team" 
        backTitle="Retour aux formations"
        back="{{ route('application.admin.formations.index', $team) }}" />
    </x-block-div>
</x-application-layout>
