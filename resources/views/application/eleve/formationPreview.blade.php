<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title  }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">
        <p>{{ $formation->description }}</p>
    </div>

    <form method="POST" action="{{ route('application.eleve.formations.enable', [$team, $formation]) }}">
        @csrf
        {{-- Token pour la formation --}}
        <input type="hidden" name="formation" value="{{ $formation->id }}">

        {{-- Affichage des fonds requis --}}
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h4 class="text-sm font-medium text-blue-800 mb-2">Coût de la formation</h4>
            <p class="text-sm text-blue-600">Cette formation coûte <strong>{{ $formation->money_amount }}€</strong></p>
            <p class="text-xs text-blue-500">Fonds disponibles dans l'équipe : <strong>{{ $team->money }}€</strong></p>
        </div>

        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            {{ __('Commencer cette formation') }}
        </button>

        @if($formation->money_amount > $team->money)
            <p class="text-red-600 text-sm mt-2">⚠️ Fonds insuffisants ! Il manque {{ $formation->money_amount - $team->money }}€</p>
        @endif
    </form>
</x-application-layout>
