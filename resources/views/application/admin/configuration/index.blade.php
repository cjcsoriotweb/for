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
    </x-block-div>


    <x-block-navigation :team="$team" back="1"/>

</x-application-layout>