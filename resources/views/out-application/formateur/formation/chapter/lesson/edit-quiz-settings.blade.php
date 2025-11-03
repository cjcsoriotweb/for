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

  @php
    $currentAction = 'settings';
    $editQuizOverviewRoute = route(
        'formateur.formation.chapter.lesson.quiz.edit',
        [$formation, $chapter, $lesson]
    );
    $editQuizTitleRoute = route(
        'formateur.formation.chapter.lesson.quiz.edit.title',
        [$formation, $chapter, $lesson]
    );
    $editQuizDescriptionRoute = route(
        'formateur.formation.chapter.lesson.quiz.edit.description',
        [$formation, $chapter, $lesson]
    );
    $editQuizSettingsRoute = route(
        'formateur.formation.chapter.lesson.quiz.edit.settings',
        [$formation, $chapter, $lesson]
    );
    $manageQuestionsRoute = route(
        'formateur.formation.chapter.lesson.quiz.questions',
        [$formation, $chapter, $lesson, $quiz]
    );
  @endphp

  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                  Parametres du quiz
                </span>
              </div>
            </li>
          </ol>
        </nav>
      </div>

      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-6 mb-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
              <div>
                <h3 class="text-lg font-semibold text-indigo-900">Actions rapides</h3>
                <p class="text-sm text-indigo-700">
                  Naviguez entre les differentes pages de modification du quiz.
                </p>
              </div>
              <div class="flex flex-wrap gap-3">
                <a
                  href="{{ $editQuizOverviewRoute }}"
                  class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $currentAction === 'overview' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                >
                  <svg class="h-5 w-5 {{ $currentAction === 'overview' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651 1.651a1.5 1.5 0 010 2.122L9.75 17.025l-3.535.707.707-3.535 8.763-8.765a1.5 1.5 0 012.122 0z" />
                  </svg>
                  Vue generale
                </a>
                <a
                  href="{{ $editQuizTitleRoute }}"
                  class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $currentAction === 'title' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                >
                  <svg class="h-5 w-5 {{ $currentAction === 'title' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.651 1.651a1.5 1.5 0 010 2.122L9.75 17.025l-3.535.707.707-3.535 8.763-8.765a1.5 1.5 0 012.122 0z" />
                  </svg>
                  Changer le titre
                </a>
                <a
                  href="{{ $editQuizDescriptionRoute }}"
                  class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $currentAction === 'description' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                >
                  <svg class="h-5 w-5 {{ $currentAction === 'description' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M12 4h9M4 9h16M4 15h16" />
                  </svg>
                  Changer la description
                </a>
                <a
                  href="{{ $editQuizSettingsRoute }}"
                  class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $currentAction === 'settings' ? 'bg-indigo-600 text-white shadow hover:bg-indigo-700' : 'border border-white bg-white text-indigo-700 shadow-sm hover:border-indigo-200 hover:text-indigo-800' }}"
                >
                  <svg class="h-5 w-5 {{ $currentAction === 'settings' ? 'text-white' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 16v-2m8-6h-2M6 12H4m13.657 5.657l-1.414-1.414M7.757 7.757L6.343 6.343m12.728 0l-1.414 1.414M7.757 16.243l-1.414 1.414" />
                  </svg>
                  Parametres du quiz
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

          <div class="max-w-2xl">
            <h2 class="text-xl font-semibold text-gray-900">Parametres du quiz</h2>
            <p class="mt-1 text-sm text-gray-600">
              Ajustez les tentatives maximum autorisees pour ce quiz.
            </p>
          </div>

          <form
            method="POST"
            action="{{ route('formateur.formation.chapter.lesson.quiz.update', [$formation, $chapter, $lesson]) }}"
            class="mt-6 space-y-6"
          >
            @csrf @method('PUT')
            <input type="hidden" name="quiz_title" value="{{ old('quiz_title', $quiz->title) }}">
            <textarea name="quiz_description" class="hidden" aria-hidden="true">{{ old('quiz_description', $quiz->description) }}</textarea>

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
              <p class="mt-2 text-xs text-gray-500">
                Saisissez un nombre entre 1 et 10, ou laissez vide pour autoriser un nombre illimite de tentatives.
              </p>
            </div>

            <div class="flex items-center justify-between">
              <a href="{{ $editQuizOverviewRoute }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                << Retour
              </a>
              <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
              >
                Enregistrer les parametres
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
