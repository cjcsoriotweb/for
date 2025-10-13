<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation :navigation="[
        ['title' => 'Changer nom', 'description' => '...', 'route' => 'application.admin.configuration.name'],
        ['title' => 'Changer logo', 'description' => '...', 'route' => 'application.admin.configuration.logo']
    ]" :team="$team" back="1" />
    
    </x-block-div>



</x-application-layout>