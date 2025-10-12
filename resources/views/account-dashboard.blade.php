<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selectionnez votre organisme') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if (Auth::user()->allTeams()->count() > 1)
                <div class="border-t border-gray-200"></div>

                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Selectionnez votre organisme') }}
                </div>

                @foreach (Auth::user()->allTeams() as $team)
                <form method="POST" action="{{ route('teams.switch', $team) }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2">
                        {{ $team->name }}
                    </button>
                </form>
                @endforeach
                @endif

                <livewire:invitations.pending-invitations />

            </div>
        </div>
    </div>
</x-app-layout>