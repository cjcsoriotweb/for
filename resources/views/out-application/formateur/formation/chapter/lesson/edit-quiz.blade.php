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
    $currentAction = $currentAction ?? 'overview';
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
    $questions = $quiz->quizQuestions()->with('quizChoices')->get();
  @endphp

  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                Choisissez l'action correspondant aux ajustements que vous souhaitez apporter.
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
          </div>

          <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
              <h3 class="text-lg font-semibold text-gray-900">Informations principales</h3>
              <p class="mt-1 text-sm text-gray-600">
                Consultez les details actuels avant de lancer une modification.
              </p>
              <dl class="mt-4 space-y-3 text-sm text-gray-700">
                <div>
                  <dt class="font-medium text-gray-900">Titre actuel</dt>
                  <dd class="mt-1 text-gray-700">{{ $quiz->title }}</dd>
                </div>
                <div>
                  <dt class="font-medium text-gray-900">Description actuelle</dt>
                  <dd class="mt-1 text-gray-700">
                    {{ $quiz->description ? $quiz->description : 'Aucune description definie.' }}
                  </dd>
                </div>
                <div>
                  <dt class="font-medium text-gray-900">Tentatives maximum</dt>
                  <dd class="mt-1 text-gray-700">
                    {{ $quiz->max_attempts ?? 'Illimite' }}
                  </dd>
                </div>
              </dl>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">Questions du quiz</h3>
                  <p class="mt-1 text-sm text-gray-600">
                    Retrouvez un apercu rapide des questions existantes.
                  </p>
                </div>
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                  {{ $questions->count() }} question(s)
                </span>
              </div>

              @if($questions->count() > 0)
              <div class="mt-4 space-y-3 max-h-48 overflow-y-auto pr-1">
                @foreach($questions->take(3) as $question)
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                  <p class="text-sm font-medium text-gray-900">
                    {{ \Illuminate\Support\Str::limit($question->question, 60) }}
                  </p>
                  <p class="mt-1 text-xs text-gray-500">
                    {{ $question->quizChoices->count() }} reponse(s) -
                    {{ $question->quizChoices->where('is_correct', true)->count() }} correcte(s)
                  </p>
                </div>
                @endforeach
                @if($questions->count() > 3)
                <p class="text-center text-xs text-gray-500">
                  ... et {{ $questions->count() - 3 }} question(s) supplementaire(s)
                </p>
                @endif
              </div>
              @else
              <div class="mt-4 rounded-lg border border-dashed border-yellow-300 bg-yellow-50 p-4 text-sm text-yellow-800">
                Aucune question n'a encore ete ajoutee. Utilisez la page dediee pour commencer.
              </div>
              @endif

              <div class="mt-4">
                <a
                  href="{{ $manageQuestionsRoute }}"
                  class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 px-4 py-2 text-sm font-medium text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-50"
                >
                  <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                  Gerer les questions
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
