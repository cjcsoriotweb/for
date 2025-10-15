<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ $formation->title }}</h3>

                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $formation->description }}</p>

                <div class="mb-4">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Chapitres</h4>
                    @forelse($formation->chapters as $chapter)
                        <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <h5 class="font-medium">{{ $chapter->title }}</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $chapter->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600 dark:text-gray-400">Aucun chapitre disponible pour le moment.</p>
                    @endforelse
                </div>

                <div class="mt-6 flex justify-start">
                    <a href="{{ route('application.eleve.formations.preview', [$team, $formation]) }}" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="material-symbols-outlined mr-2">play_arrow</span>
                        Commencer la formation
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-application-layout>
