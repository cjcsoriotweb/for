<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        <x-slot name="block">
            Configuration de votre application
        </x-slot>
        @livewire('teams.update-team-name-form', ['team' => $team])
    </x-block-div>

    <x-block-div>
        @include('application.admin.blocks.block-include', ['team' => $team])
    </x-block-div>
</x-application-layout>