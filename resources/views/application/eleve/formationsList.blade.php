<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">

                @livewire('FormationList', ['team' => $team, 'display'=>'eleve'])

            </div>
        </div>
    </div>
    <x-block-div>
        <x-block-navigation :navigation="[

        ]" card="bg-red-500" :team="$team" back="{{ route('application.eleve.index',$team) }}" />

    </x-block-div>

</x-application-layout>
