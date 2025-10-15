<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Superadmin') }}
            @livewire('notifications-bell')
        </h2>
        
    </x-slot>

    <div class="py-12">

    <x-block-div>

        @livewire('teams.create-team-form')

    </x-block-div>

    </div>
</x-app-layout>