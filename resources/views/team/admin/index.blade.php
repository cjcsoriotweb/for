<x-team-layout :team="$team">
    <x-slot name="header">

        <x-admin-navigation :team="$team" />

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @livewire('teams.update-team-name-form', ['team' => $team])

            <!-- Money Counter -->


        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            <div
                class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200 font-semibold">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                Montant disponible aloué aux formations {{ $team->money }}€
            </div>
        </div>

    </div>
</x-team-layout>