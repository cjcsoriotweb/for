<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        @livewire('formationList', ['team' => $team,'display'=>'admin'])
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