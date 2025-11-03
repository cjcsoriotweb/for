<x-app-layout>
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-2xl text-white leading-tight">
                            {{ __('Questions du quiz d\'entrée') }}
                        </h2>
                        <p class="text-blue-100 text-sm">{{ $formation->title }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
                       class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition-all duration-200 border border-white/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuration du quiz
                    </a>
                    <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                       class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white font-medium rounded-lg transition-all duration-200 border border-white/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux chapitres
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations du quiz -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-xl mb-8 border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-3 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                                @if($quiz->description)
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $quiz->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-900/20 dark:to-emerald-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Score de passage</p>
                                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $quiz->passing_score }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Questions totales</p>
                                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $questions->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Type de quiz</p>
                                    <p class="text-lg font-bold text-purple-900 dark:text-purple-100">Quiz d'entrée</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des questions existantes -->
            @if($questions->count() > 0)
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-xl mb-8 border border-gray-200 dark:border-gray-700">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">Questions existantes</h4>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ $questions->count() }} question{{ $questions->count() > 1 ? 's' : '' }}
                            </span>
                        </div>

                        <div class="grid gap-6">
                            @foreach($questions as $index => $question)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-start space-x-4 flex-1">
                                            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shadow-lg">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-lg text-gray-900 dark:text-white mb-2 leading-tight">{{ $question->question }}</h5>
                                                <div class="flex items-center space-x-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $question->type === 'multiple_choice' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="{{ $question->type === 'multiple_choice' ? 'M4 6h16M4 10h16M4 14h16M4 18h16' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                                                        </svg>
                                                        {{ $question->type === 'multiple_choice' ? 'Choix multiple' : 'Vrai/Faux' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <button type="button"
                                                    onclick="editQuestion({{ $question->id }}, '{{ addslashes($question->question) }}', '{{ $question->type }}', {{ $question->quizChoices->toJson() }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 hover:text-blue-800 text-sm font-medium rounded-lg transition-colors duration-200 border border-blue-200 hover:border-blue-300">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Modifier
                                            </button>
                                            <form method="POST" action="{{ route('formateur.formation.entry-quiz.questions.delete', [$formation, $question]) }}"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 text-sm font-medium rounded-lg transition-colors duration-200 border border-red-200 hover:border-red-300">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Réponses possibles</span>
                                        </div>
                                        <div class="grid gap-2">
                                            @foreach($question->quizChoices as $choice)
                                                <div class="flex items-center space-x-3 p-3 rounded-lg {{ $choice->is_correct ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-gray-50 dark:bg-gray-800' }}">
                                                    <div class="flex-shrink-0">
                                                        @if($choice->is_correct)
                                                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="w-5 h-5 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm {{ $choice->is_correct ? 'text-green-800 dark:text-green-200 font-medium' : 'text-gray-700 dark:text-gray-300' }}">
                                                        {{ $choice->choice_text }}
                                                    </span>
                                                    @if($choice->is_correct)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 ml-auto">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Correct
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire d'ajout de question -->
            <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-3 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white" id="form-title">Ajouter une nouvelle question</h4>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400" id="form-subtitle">
                            Créez une question pour votre quiz d'entrée
                        </div>
                    </div>

                    <form id="question-form" method="POST" action="{{ route('formateur.formation.entry-quiz.questions.store', $formation) }}">
                        @csrf

                        <!-- Type de question -->
                        <div class="mb-6">
                            <label for="question_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Type de question
                                </span>
                            </label>
                            <div class="relative">
                                <select id="question_type" name="question_type"
                                        class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm transition-colors duration-200" required>
                                    <option value="multiple_choice">📋 Choix multiple</option>
                                    <option value="true_false">✅ Vrai/Faux</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error for="question_type" class="mt-2" />
                        </div>

                        <!-- Texte de la question -->
                        <div class="mb-6">
                            <label for="question_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Question
                                </span>
                            </label>
                            <textarea id="question_text" name="question_text" rows="4"
                                      class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg shadow-sm transition-colors duration-200 resize-none"
                                      required placeholder="Saisissez votre question ici... Exemple : Quelle est la capitale de la France ?">{{ old('question_text') }}</textarea>
                            <x-input-error for="question_text" class="mt-2" />
                        </div>

                        <!-- Réponses -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Réponses possibles
                                </span>
                            </label>
                            <div id="choices-container" class="space-y-3">
                                <!-- Les choix seront ajoutés dynamiquement par JavaScript -->
                            </div>
                            <button type="button" id="add-choice-btn"
                                    class="inline-flex items-center mt-4 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Ajouter une réponse
                            </button>
                            <x-input-error for="choices" class="mt-2" />
                        </div>

                        <!-- Champ caché pour les choix JSON -->
                        <input type="hidden" id="choices-input" name="choices" value="[]">

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" id="cancel-edit-btn"
                                    class="hidden inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Annuler
                            </button>
                            <x-primary-button id="submit-btn" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ajouter la question
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let choiceCounter = 0;
        let editingQuestionId = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeChoices();
            document.getElementById('question_type').addEventListener('change', handleQuestionTypeChange);
            document.getElementById('add-choice-btn').addEventListener('click', addChoice);
            document.getElementById('cancel-edit-btn').addEventListener('click', cancelEdit);
            document.getElementById('question-form').addEventListener('submit', prepareChoicesData);
        });

        function initializeChoices() {
            const questionType = document.getElementById('question_type').value;
            updateChoicesForType(questionType);
        }

        function handleQuestionTypeChange() {
            const questionType = this.value;
            updateChoicesForType(questionType);
        }

        function updateChoicesForType(type) {
            const container = document.getElementById('choices-container');
            container.innerHTML = '';

            if (type === 'true_false') {
                addTrueFalseChoices();
            } else {
                // Pour les choix multiples, commencer avec 2 choix vides
                addChoice();
                addChoice();
            }
        }

        function addTrueFalseChoices() {
            addChoiceField('Vrai', true);
            addChoiceField('Faux', false);
        }

        function addChoice() {
            const questionType = document.getElementById('question_type').value;
            if (questionType === 'true_false') return;

            addChoiceField('', false);
        }

        function addChoiceField(text = '', isCorrect = false) {
            const container = document.getElementById('choices-container');
            const choiceId = `choice-${choiceCounter++}`;
            const questionType = document.getElementById('question_type').value;

            const choiceDiv = document.createElement('div');
            choiceDiv.className = 'flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700';
            choiceDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <input type="radio" name="correct-choice" value="${choiceId}" ${isCorrect ? 'checked' : ''}
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                </div>
                <input type="text" value="${text}" placeholder="Saisir la réponse..."
                       class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md transition-colors duration-200">
                <div class="flex items-center space-x-2">
                    <label class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="mr-2">Correct</span>
                    </label>
                    ${questionType === 'true_false' ? '' : `
                        <button type="button" onclick="removeChoice(this)"
                                class="inline-flex items-center px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 text-sm font-medium rounded-md transition-colors duration-200 border border-red-200 hover:border-red-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    `}
                </div>
            `;

            container.appendChild(choiceDiv);
        }

        function removeChoice(button) {
            button.closest('.flex').remove();
        }

        function prepareChoicesData(e) {
            const choices = [];
            const choiceElements = document.querySelectorAll('#choices-container .flex');
            const correctChoiceValue = document.querySelector('input[name="correct-choice"]:checked')?.value;

            choiceElements.forEach((element, index) => {
                const input = element.querySelector('input[type="text"]');
                const choiceId = `choice-${index}`;

                choices.push({
                    text: input.value.trim(),
                    is_correct: correctChoiceValue === choiceId
                });
            });

            document.getElementById('choices-input').value = JSON.stringify(choices);
        }

        function editQuestion(questionId, questionText, questionType, choicesJson) {
            editingQuestionId = questionId;

            // Changer le titre du formulaire
            document.getElementById('form-title').textContent = 'Modifier la question';
            document.getElementById('submit-btn').textContent = 'Mettre à jour la question';
            document.getElementById('cancel-edit-btn').classList.remove('hidden');

            // Remplir les champs
            document.getElementById('question_type').value = questionType;
            document.getElementById('question_text').value = questionText;

            // Changer l'action du formulaire
            const form = document.getElementById('question-form');
            form.action = `/formateur/formation/${{{ $formation->id }}}/entry-quiz/questions/${questionId}`;
            form.method = 'POST';

            // Ajouter le champ _method pour PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            // Remplir les choix
            const choices = JSON.parse(choicesJson);
            updateChoicesForEdit(questionType, choices);

            // Scroll vers le formulaire
            form.scrollIntoView({ behavior: 'smooth' });
        }

        function updateChoicesForEdit(questionType, choices) {
            const container = document.getElementById('choices-container');
            container.innerHTML = '';
            choiceCounter = 0;

            choices.forEach((choice, index) => {
                const choiceId = `choice-${choiceCounter++}`;
                const choiceDiv = document.createElement('div');
                choiceDiv.className = 'flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700';
                choiceDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <input type="radio" name="correct-choice" value="${choiceId}" ${choice.is_correct ? 'checked' : ''}
                               class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    </div>
                    <input type="text" value="${choice.choice_text}" placeholder="Saisir la réponse..."
                           class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md transition-colors duration-200">
                    <div class="flex items-center space-x-2">
                        <label class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span class="mr-2">Correct</span>
                        </label>
                        ${questionType === 'true_false' ? '' : `
                            <button type="button" onclick="removeChoice(this)"
                                    class="inline-flex items-center px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 text-sm font-medium rounded-md transition-colors duration-200 border border-red-200 hover:border-red-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        `}
                    </div>
                `;

                container.appendChild(choiceDiv);
            });
        }

        function cancelEdit() {
            editingQuestionId = null;

            // Remettre le titre original
            document.getElementById('form-title').textContent = 'Ajouter une nouvelle question';
            document.getElementById('submit-btn').textContent = 'Ajouter la question';
            document.getElementById('cancel-edit-btn').classList.add('hidden');

            // Remettre l'action du formulaire
            const form = document.getElementById('question-form');
            form.action = '{{ route("formateur.formation.entry-quiz.questions.store", $formation) }}';
            form.method = 'POST';

            // Supprimer le champ _method
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }

            // Vider les champs
            document.getElementById('question_text').value = '';
            initializeChoices();
        }
    </script>
</x-app-layout>
