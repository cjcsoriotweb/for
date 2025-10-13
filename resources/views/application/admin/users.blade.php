<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    @include('application.admin.navbar', ['team' => $team])


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-slot name="block">
                Gestion des utilisateurs de votre application
            </x-slot>

            @livewire('teams.team-member-manager', ['team' => $team])



        </div>
    </div>
</x-application-layout>
