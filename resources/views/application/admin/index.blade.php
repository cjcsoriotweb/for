<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('teams.update-team-name-form', ['team' => $team])

            @include('teams.partials.update-team-photo-form', ['team' => $team])


            @livewire('teams.team-member-manager', ['team' => $team])



        </div>
    </div>
</x-application-layout>
