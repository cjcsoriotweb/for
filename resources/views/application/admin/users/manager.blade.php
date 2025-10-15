<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
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