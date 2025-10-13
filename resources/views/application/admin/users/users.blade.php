<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion Utilisateurs') }}
        </h2>
    </x-slot>

    <x-block-div>
        <x-slot name="block">
            Gestion des utilisateurs de votre application
        </x-slot>
        @livewire('teams.team-member-manager', ['team' => $team])
    </x-block-div>


</x-application-layout>
