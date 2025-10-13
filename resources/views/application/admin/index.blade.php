<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <x-block-div>
        
        <x-block-navigation :navigation="[
            ['title' => 'Configuration', 'description' => '..', 'route' => 'application.admin.configuration.index']
        ]" :team="$team" back="0"/>



    </x-block-div>


</x-application-layout>