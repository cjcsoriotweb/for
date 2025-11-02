<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour aux chapitres
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('formateur.formation.entry-quiz.store', $formation) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Configuration du quiz d'entrée</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Titre du quiz -->
                                <div>
                                    <x-input-label for="title" :value="__('Titre du quiz')" />
                                    <x-text-input id="title" name="title" type="text"
                                                  :value="old('title', $quiz->title ?? '')"
                                                  class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <!-- Score de passage -->
                                <div>
                                    <x-input-label for="passing_score" :value="__('Score de passage (%)')" />
                                    <x-text-input id="passing_score" name="passing_score" type="number" min="0" max="100"
                                                  :value="old('passing_score', $quiz->passing_score ?? 80)"
                                                  class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('passing_score')" class="mt-2" />
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Score minimum requis pour accéder à la formation (défaut: 80%)
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <x-input-label for="description" :value="__('Description (optionnelle)')" />
                                <textarea id="description" name="description" rows="3"
                                          class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          placeholder="Décrivez brièvement l'objectif de ce quiz d'entrée...">{{ old('description', $quiz->description ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            @if($quiz)
                                <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Gérer les questions
                                </a>
                            @endif
                            <x-primary-button>
                                {{ $quiz ? 'Mettre à jour' : 'Créer' }} le quiz
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
