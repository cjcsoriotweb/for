<x-app-layout>
    <!-- Formation Details -->
    @if($errors->any())
    <div
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
        role="alert"
    >
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline"
            >Veuillez corriger les erreurs suivantes:</span
        >
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a
                                href="{{
                                    route(
                                        'formateur.formation.edit',
                                        $formation
                                    )
                                }}"
                                class="text-sm text-gray-700 hover:text-indigo-600"
                            >
                                {{ $formation->title }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.edit',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    class="text-sm text-gray-700 hover:text-indigo-600 ml-1"
                                >
                                    Chapitre {{ $chapter->position }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1"
                                    >Modifier le Quiz</span
                                >
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg
                                class="w-6 h-6 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                Modifier le Quiz
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Modifiez les paramètres de votre quiz existant
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.formation.chapter.lesson.quiz.update',
                                [$formation, $chapter, $lesson]
                            )
                        }}"
                        class="space-y-6"
                    >
                        @csrf @method('PUT')

                        <!-- Quiz Title -->
                        <div>
                            <label
                                for="quiz_title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Titre du Quiz *
                            </label>
                            <input
                                type="text"
                                id="quiz_title"
                                name="quiz_title"
                                value="{{ old('quiz_title', $quiz->title) }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_title') border-red-500 @enderror"
                                placeholder="Ex: Quiz sur les concepts de base"
                                required
                            />
                            @error('quiz_title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Quiz Description -->
                        <div>
                            <label
                                for="quiz_description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Description du Quiz
                            </label>
                            <textarea
                                id="quiz_description"
                                name="quiz_description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_description') border-red-500 @enderror"
                                placeholder="Décrivez brièvement le contenu de ce quiz..."
                                >{{ old("quiz_description", $quiz->description) }}</textarea
                            >
                            @error('quiz_description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Quiz Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="passing_score"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Score de réussite (%) *
                                </label>
                                <input
                                    type="number"
                                    id="passing_score"
                                    name="passing_score"
                                    value="{{ old('passing_score', $quiz->passing_score) }}"
                                    min="0"
                                    max="100"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('passing_score') border-red-500 @enderror"
                                    required
                                />
                                @error('passing_score')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    for="max_attempts"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nombre maximum de tentatives
                                </label>
                                <input
                                    type="number"
                                    id="max_attempts"
                                    name="max_attempts"
                                    value="{{ old('max_attempts', $quiz->max_attempts) }}"
                                    min="1"
                                    max="10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_attempts') border-red-500 @enderror"
                                />
                                @error('max_attempts')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    Laisser vide pour tentatives illimitées
                                </p>
                            </div>
                        </div>

                        <!-- Quiz Questions Section -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        Questions du Quiz
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Gérez les questions et réponses de votre
                                        quiz
                                    </p>
                                </div>
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.lesson.quiz.questions',
                                            [
                                                $formation,
                                                $chapter,
                                                $lesson,
                                                $quiz
                                            ]
                                        )
                                    }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        ></path>
                                    </svg>
                                    Gérer les Questions
                                </a>
                            </div>

                            @php $questions =
                            $quiz->quizQuestions()->with('quizChoices')->get();
                            @endphp @if($questions->count() > 0)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                    >
                                        {{ $questions->count() }} question(s)
                                        dans ce quiz
                                    </span>
                                </div>
                                <div class="space-y-2 max-h-40 overflow-y-auto">
                                    @foreach($questions->take(3) as $question)
                                    <div class="bg-white rounded border p-3">
                                        <div
                                            class="flex items-start justify-between"
                                        >
                                            <div class="flex-1">
                                                <p
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ Str::limit($question->question, 60) }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    {{ $question->quizChoices->count() }}
                                                    réponse(s) •
                                                    {{ $question->quizChoices->where('is_correct', true)->count() }}
                                                    correcte(s)
                                                </p>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}"
                                            >
                                                {{ $question->type === 'multiple_choice' ? 'QCM' : 'V/F' }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach @if($questions->count() > 3)
                                    <div class="text-center py-2">
                                        <span class="text-sm text-gray-500">
                                            ... et
                                            {{ $questions->count() - 3 }}
                                            autre(s) question(s)
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div
                                class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"
                            >
                                <div class="flex items-center">
                                    <svg
                                        class="w-5 h-5 text-yellow-400 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-yellow-800"
                                        >
                                            Aucune question définie
                                        </p>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            Ajoutez des questions pour que vos
                                            apprenants puissent passer le quiz.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-between pt-6 border-t border-gray-200"
                        >
                            <a
                                href="{{
                                    route('formateur.formation.edit', [
                                        $formation,
                                        $chapter
                                    ])
                                }}"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                            >
                                ← Retour aux leçons
                            </a>
                            <div class="space-x-3">
                                <button
                                    type="button"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Mettre à Jour le Quiz
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
