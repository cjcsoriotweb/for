<x-application-layout :team="$team">
    <x-slot name="header">

            <x-admin-navigation  :team="$team" />

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @livewire('teams.team-member-manager', ['team' => $team])
               

        </div>
    </div>
</x-application-layout>
