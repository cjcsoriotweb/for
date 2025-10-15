<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title  }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">
        <p>{{ $formation->description }}</p>
    </div>

    <a href="{{ route('application.eleve.formation.enable', [$team, $formation]) }}" type="button" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">{{ __('Commencer cette formation') }}</a>
</x-application-layout>