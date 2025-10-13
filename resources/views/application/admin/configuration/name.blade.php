<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        <x-slot name="block">
            Changez le nom de votre application
        </x-slot>
        @livewire('teams.update-team-name-form', ['team' => $team])
    </x-block-div>


    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">



    </section>

</x-application-layout>