<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        @livewire('teams.team-member-manager', ['team' => $team])

    </x-block-div>

    <x-block-div>
        <x-block-navigation :navigation="[]" :team="$team" backTitle="Retour à Administration"
            back="{{ route('application.admin.index', $team) }}" />
    </x-block-div>
</x-application-layout>