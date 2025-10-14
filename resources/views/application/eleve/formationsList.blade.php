<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                @livewire('formationList', ['team' => $team, 'display'=>'user'])

            </div>
        </div>
    </div>
    <x-block-div>
        <x-block-navigation :navigation="[

        ]" card="bg-red-500" :team="$team" back="{{ route('application.eleve.index',$team) }}" />

    </x-block-div>

</x-application-layout>