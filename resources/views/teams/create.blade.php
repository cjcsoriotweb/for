<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Create Team') }}
        </h2>
    </x-slot>

    @if(!Auth::user()->superadmin)
        <p>Vous n'êtes pas superadmin</p>
    @else
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
    @endif
</x-app-layout>
