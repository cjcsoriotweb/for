<x-app-layout>
  @if($errors->any())
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
    <strong class="font-bold">Erreur !</strong>
    <span class="block sm:inline">Veuillez corriger les erreurs suivantes :</span>
    <ul class="mt-2 list-disc list-inside">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      @php
        $activeAction = request()->input('action', 'overview');
        $editQuizRoute = route(
            'formateur.formation.chapter.lesson.quiz.edit',
            [$formation, $chapter, $lesson]
        );
        $manageQuestionsRoute = route(
            'formateur.formation.chapter.lesson.quiz.questions',
            [$formation, $chapter, $lesson, $quiz]
        );
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();
      @endphp

      <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a
                href="{{ route('formateur.formation.show', $formation) }}"
                class="text-sm text-gray-700 hover:text-indigo-600"
              >
                {{ $formation->title }}
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                <a
                  href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}"
                  class="text-sm text-gray-700 hover:text-indigo-600 ml-1"
                >
                  Chapitre {{ $chapter->position }}
                </a>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                <span class="text-sm text-gray-500 ml-1">
                  Modifier le quiz
                </span>
              </div>
            </li>
          </ol>
        </nav>
      </div>

      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="flex items-center mb-6">
            <div class="bg-indigo-100 rounded-full p-3 mr-4">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                ></path>
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Modifier le quiz</h2>
              <p class="text-gray-600 mt-1">
                Choisissez l'action qui correspond le mieux aux modifications que vous souhaitez apporter.
              </p>
            </div>
          </div>

          <div class="mb-8">
            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-6">
              <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                  <h3 class="text-lg font-semibold text-indigo-900">Actions rapides</h3>
                  <p class="text-sm text-indigo-700">
                    Accedez rapidement aux pages dediees pour ajuster votre quiz.
                  </p>
                </div>
                <div class="flex flex-wrap gap-3">
                  <a
                    href="{{ $editQuizRoute }}"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeAction === 'overview' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                  >
                    <svg class="h-5 w-5 {{ $activeAction === 'overview' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651 1.651a1.5 1.5 0 010 2.122L9.75 17.025l-3.535.707.707-3.535 8.763-8.765a1.5 1.5 0 012.122 0z" />
                    </svg>
                    Vue generale
                  </a>
                  <a
                    href="{{ $editQuizRoute }}?action=title"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeAction === 'title' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                  >
                    <svg class="h-5 w-5 {{ $activeAction === 'title' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651 1.651a1.5 1.5 0 010 2.122L9.75 17.025l-3.535.707.707-3.535 8.763-8.765a1.5 1.5 0 012.122 0z" />
                    </svg>
                    Changer le titre
                  </a>
                  <a
                    href="{{ $editQuizRoute }}?action=description"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $activeAction === 'description' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                  >
                    <svg class="h-5 w-5 {{ $activeAction === 'description' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M12 4h9M4 9h16M4 15h16" />
                    </svg>
                    Changer la description
                  </a>
                  <a
                    href="{{ $manageQuestionsRoute }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white/70 px-4 py-2 text-sm font-medium text-indigo-900 backdrop-blur transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Gerer les questions
                  </a>
                </div>
              </div>
            </div>
          </div>

          @if($activeAction === 'overview')
          <form
            method="POST"
            action="{{ route('formateur.formation.chapter.lesson.quiz.update', [$formation, $chapter, $lesson]) }}"
            class="space-y-6"
          >
            @csrf @method('PUT')

            <div>
              <label for="quiz_title" class="block text-sm font-medium text-gray-700 mb-2">
                Titre du quiz *
              </label>
              <input
                type="text"
                id="quiz_title"
                name="quiz_title"
                value="{{ old('quiz_title', $quiz->title) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_title') border-red-500 @enderror"
                placeholder="Ex : Quiz de revision du chapitre"
                required
              />
              @error('quiz_title')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <div>
              <label for="quiz_description" class="block text-sm font-medium text-gray-700 mb-2">
                Description du quiz
              </label>
              <textarea
                id="quiz_description"
                name="quiz_description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_description') border-red-500 @enderror"
                placeholder="Expliquez en quelques lignes le contenu et les objectifs de ce quiz."
              >{{ old('quiz_description', $quiz->description) }}</textarea>
              @error('quiz_description')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <div>
              <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                Tentatives maximum
              </label>
              <input
                type="number"
                id="max_attempts"
                name="max_attempts"
                min="1"
                max="10"
                value="{{ old('max_attempts', $quiz->max_attempts) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('max_attempts') border-red-500 @enderror"
                placeholder="Laisser vide pour illimite"
              />
              @error('max_attempts')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <div class="pt-6 border-t border-gray-200">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">
                    Questions du quiz
                  </h3>
                  <p class="text-sm text-gray-600 mt-1">
                    Gerer les questions et reponses directement depuis la page dediee.
                  </p>
                </div>
                <a
                  href="{{ $manageQuestionsRoute }}"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex items-center"
                >
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  Gerer les questions
                </a>
              </div>

              @if($questions->count() > 0)
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-sm font-medium text-gray-700">
                    {{ $questions->count() }} question(s) dans ce quiz
                  </span>
                </div>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                  @foreach($questions->take(3) as $question)
                  <div class="bg-white rounded border p-3">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">
                          {{ Str::limit($question->question, 60) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                          {{ $question->quizChoices->count() }} reponse(s) -
                          {{ $question->quizChoices->where('is_correct', true)->count() }} correcte(s)
                        </p>
                      </div>
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ $question->type === 'multiple_choice' ? 'QCM' : 'V/F' }}
                      </span>
                    </div>
                  </div>
                  @endforeach
                  @if($questions->count() > 3)
                  <div class="text-center py-2">
                    <span class="text-sm text-gray-500">
                      ... et {{ $questions->count() - 3 }} question(s) supplementaire(s)
                    </span>
                  </div>
                  @endif
                </div>
              </div>
              @else
              <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  <div>
                    <p class="text-sm font-medium text-yellow-800">
                      Aucune question definie
                    </p>
                    <p class="text-sm text-yellow-700 mt-1">
                      Utilisez la page Gerer les questions pour ajouter votre premiere question.
                    </p>
                  </div>
                </div>
              </div>
              @endif
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <a
                href="{{ route('formateur.formation.show', $formation) }}"
                class="text-gray-600 hover:text-gray-900 text-sm font-medium"
              >
                << Retour a la formation
              </a>
              <div class="space-x-3">
                <a
                  href="{{ $editQuizRoute }}"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Annuler
                </a>
                <button
                  type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Mettre a jour le quiz
                </button>
              </div>
            </div>
          </form>
          @elseif($activeAction === 'title')
          <div class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="max-w-2xl">
              <h3 class="text-xl font-semibold text-gray-900">Changer le titre du quiz</h3>
              <p class="mt-1 text-sm text-gray-600">
                Donnez un titre clair et motivant qui represente le contenu du quiz.
              </p>
            </div>
            <form
              method="POST"
              action="{{ route('formateur.formation.chapter.lesson.quiz.update', [$formation, $chapter, $lesson]) }}"
              class="mt-6 space-y-6"
            >
              @csrf @method('PUT')
              <textarea name="quiz_description" class="hidden" aria-hidden="true">{{ old('quiz_description', $quiz->description) }}</textarea>
              <input type="hidden" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}">

              <div>
                <label for="quiz_title" class="block text-sm font-medium text-gray-700 mb-2">
                  Nouveau titre *
                </label>
                <input
                  type="text"
                  id="quiz_title"
                  name="quiz_title"
                  value="{{ old('quiz_title', $quiz->title) }}"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_title') border-red-500 @enderror"
                  placeholder="Ex : Quiz de validation du chapitre 1"
                  required
                />
                @error('quiz_title')
                <p class="mt-1 text-sm text-red-600">
                  {{ $message }}
                </p>
                @enderror
              </div>

              <div class="flex items-center justify-between">
                <a href="{{ $editQuizRoute }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                  << Retour
                </a>
                <button
                  type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Enregistrer le titre
                </button>
              </div>
            </form>
          </div>
          @elseif($activeAction === 'description')
          <div class="rounded-xl border border-gray-200 bg-white p-6">
            <div class="max-w-2xl">
              <h3 class="text-xl font-semibold text-gray-900">Changer la description du quiz</h3>
              <p class="mt-1 text-sm text-gray-600">
                Precisez l'objectif ou les consignes pour accompagner vos apprenants.
              </p>
            </div>
            <form
              method="POST"
              action="{{ route('formateur.formation.chapter.lesson.quiz.update', [$formation, $chapter, $lesson]) }}"
              class="mt-6 space-y-6"
            >
              @csrf @method('PUT')
              <input type="hidden" name="quiz_title" value="{{ old('quiz_title', $quiz->title) }}">
              <input type="hidden" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}">

              <div>
                <label for="quiz_description" class="block text-sm font-medium text-gray-700 mb-2">
                  Nouvelle description
                </label>
                <textarea
                  id="quiz_description"
                  name="quiz_description"
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('quiz_description') border-red-500 @enderror"
                  placeholder="Decrivez brievement le contenu et les attentes de ce quiz..."
                >{{ old('quiz_description', $quiz->description) }}</textarea>
                @error('quiz_description')
                <p class="mt-1 text-sm text-red-600">
                  {{ $message }}
                </p>
                @enderror
              </div>

              <div class="flex items-center justify-between">
                <a href="{{ $editQuizRoute }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                  << Retour
                </a>
                <button
                  type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Enregistrer la description
                </button>
              </div>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
