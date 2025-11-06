<x-app-layout>
    <header>
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour aux chapitres
            </a>
        </div>
    </header>

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
                                    <x-label for="title" :value="__('Titre du quiz')" />
                                    <x-input id="title" name="title" type="text"
                                             :value="old('title', $quiz->title ?? '')"
                                             class="mt-1 block w-full" required />
                                    <x-input-error for="title" class="mt-2" />
                                </div>

                                <!-- Seuil minimum -->
                                <div>
                                    <x-label for="entry_min_score" :value="__('Score minimum accepté (%)')" />
                                    <x-input id="entry_min_score" name="entry_min_score" type="number" min="0" max="100"
                                             :value="old('entry_min_score', $quiz->entry_min_score ?? 0)"
                                             class="mt-1 block w-full" required />
                                    <x-input-error for="entry_min_score" class="mt-2" />
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        En dessous de ce score, le niveau est considéré comme insuffisant.
                                    </p>
                                </div>

                                <!-- Seuil maximum -->
                                <div>
                                    <x-label for="entry_max_score" :value="__('Score maximum accepté (%)')" />
                                    <x-input id="entry_max_score" name="entry_max_score" type="number" min="0" max="100"
                                             :value="old('entry_max_score', $quiz->entry_max_score ?? ($quiz->passing_score ?? 100))"
                                             class="mt-1 block w-full" required />
                                    <x-input-error for="entry_max_score" class="mt-2" />
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Au-dessus de ce score, le niveau est jugé trop élevé pour la formation.
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6">
                                <x-label for="description" :value="__('Description (optionnelle)')" />
                                <textarea id="description" name="description" rows="3"
                                          class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                          placeholder="Décrivez brièvement l'objectif de ce quiz d'entrée...">{{ old('description', $quiz->description ?? '') }}</textarea>
                                <x-input-error for="description" class="mt-2" />
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

