<x-app-layout>
  @if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
      <p class="font-semibold">Une action est requise</p>
      <p class="mt-1">Corrigez les points ci-dessous avant de continuer :</p>
      <ul class="mt-3 list-disc space-y-1 pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @php
    $currentAction = $currentAction ?? 'overview';
    $editQuizOverviewRoute = route('formateur.formation.chapter.lesson.quiz.edit', [$formation, $chapter, $lesson]);
    $editQuizTitleRoute = route('formateur.formation.chapter.lesson.quiz.edit.title', [$formation, $chapter, $lesson]);
    $editQuizDescriptionRoute = route('formateur.formation.chapter.lesson.quiz.edit.description', [$formation, $chapter, $lesson]);
    $editQuizSettingsRoute = route('formateur.formation.chapter.lesson.quiz.edit.settings', [$formation, $chapter, $lesson]);
    $manageQuestionsRoute = route('formateur.formation.chapter.lesson.quiz.questions', [$formation, $chapter, $lesson, $quiz]);

    $questions = $quiz->quizQuestions()->with('quizChoices')->get();
    $questionCount = $questions->count();
    $questionsMissingCorrect = 0;

    foreach ($questions as $question) {
        if ($question->quizChoices->where('is_correct', true)->count() === 0) {
            $questionsMissingCorrect++;
        }
    }

    $hasTitle = filled($quiz->title);
    $hasDescription = filled($quiz->description);
    $hasSettings = ! is_null($quiz->max_attempts);
    $hasQuestions = $questionCount > 0;

    $checklist = [
        [
            'label' => 'Titre du quiz',
            'description' => 'Un titre clair aide vos apprenants a comprendre le contexte.',
            'done' => $hasTitle,
            'route' => $editQuizTitleRoute,
        ],
        [
            'label' => 'Description',
            'description' => 'Expliquez en quelques lignes ce qui est attendu.',
            'done' => $hasDescription,
            'route' => $editQuizDescriptionRoute,
        ],
        [
            'label' => 'Parametres',
            'description' => 'Definissez les tentatives, la note minimale et la duree.',
            'done' => $hasSettings,
            'route' => $editQuizSettingsRoute,
        ],
        [
            'label' => 'Questions',
            'description' => 'Ajoutez les questions et marquez la bonne reponse.',
            'done' => $hasQuestions && $questionsMissingCorrect === 0,
            'route' => $manageQuestionsRoute,
        ],
    ];

    $completedSteps = collect($checklist)->where('done', true)->count();
    $totalSteps = max(count($checklist), 1);
    $completionRate = (int) round(($completedSteps / $totalSteps) * 100);

    $nextStep = collect($checklist)->firstWhere('done', false);

    $quizEstimatedDuration = $quiz->estimated_duration_minutes;
    if (! $quizEstimatedDuration && $questionCount > 0) {
        $quizEstimatedDuration = max($questionCount * 2, 5);
    }
  @endphp

  <div class="py-10">
    <div class="mx-auto max-w-5xl space-y-8 px-4 sm:px-6 lg:px-0">
      <nav class="text-sm text-gray-500" aria-label="Fil d Ariane">
        <ol class="inline-flex items-center gap-2">
          <li>
            <a href="{{ route('formateur.formation.show', $formation) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
              {{ $formation->title }}
            </a>
          </li>
          <li>/</li>
          <li>
            <a href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
              Chapitre {{ $chapter->position }}
            </a>
          </li>
          <li>/</li>
          <li class="text-gray-600">Quiz</li>
        </ol>
      </nav>

      <header class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
          <div>
            <p class="text-sm text-gray-500">Chapitre {{ $chapter->position }} - {{ $chapter->title }}</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-900">
              {{ $quiz->title ?? 'Quiz sans titre' }}
            </h1>
            <p class="mt-2 text-sm text-gray-600">
              Tout est rassemble ici pour avancer sans se perdre dans les menus.
            </p>
            @if ($nextStep)
              <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                Prochaine action : {{ $nextStep['label'] }}
              </div>
            @endif
          </div>
          <div class="grid w-full gap-4 text-center text-sm text-gray-600 sm:grid-cols-3 md:w-auto">
            <div class="rounded-xl border border-gray-100 px-5 py-3">
              <p class="text-2xl font-semibold text-gray-900">{{ $questionCount }}</p>
              <p class="mt-1">Questions</p>
            </div>
            <div class="rounded-xl border border-gray-100 px-5 py-3">
              <p class="text-2xl font-semibold text-gray-900">{{ $quiz->max_attempts ?? 'Illimite' }}</p>
              <p class="mt-1">Tentatives</p>
            </div>
            <div class="rounded-xl border border-gray-100 px-5 py-3">
              <p class="text-2xl font-semibold text-gray-900">{{ $quizEstimatedDuration ? $quizEstimatedDuration.' min' : '--' }}</p>
              <p class="mt-1">Duree estimee</p>
            </div>
          </div>
        </div>
      </header>

      <div class="grid gap-6 lg:grid-cols-3">
        <section class="space-y-6 lg:col-span-2">
          <article class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Configuration rapide</h2>
                <p class="text-sm text-gray-600">Quatre etapes suffisent pour rendre le quiz publiable.</p>
              </div>
              <span class="text-sm font-semibold text-gray-700">{{ $completionRate }}% pret</span>
            </div>
            <ul class="divide-y divide-gray-100">
              @foreach ($checklist as $step)
                <li class="flex flex-col gap-3 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                  <div class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full {{ $step['done'] ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    <div>
                      <p class="font-medium text-gray-900">{{ $step['label'] }}</p>
                      <p class="text-sm text-gray-600">{{ $step['description'] }}</p>
                    </div>
                  </div>
                  <a href="{{ $step['route'] }}" class="inline-flex items-center gap-2 text-sm font-semibold {{ $step['done'] ? 'text-emerald-600 hover:text-emerald-700' : 'text-indigo-600 hover:text-indigo-700' }}">
                    {{ $step['done'] ? 'Modifier' : 'Completer' }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </a>
                </li>
              @endforeach
            </ul>
          </article>

          <article class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900">Questions</h2>
              <div class="flex gap-3">
                <a href="{{ $manageQuestionsRoute }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                  Gerer les questions
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </a>
              </div>
            </div>

            @if ($questionCount > 0)
              <ul class="divide-y divide-gray-100">
                @foreach ($questions->take(4) as $question)
                  @php
                    $correctCount = $question->quizChoices->where('is_correct', true)->count();
                  @endphp
                  <li class="px-6 py-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Question {{ $loop->iteration }}</p>
                    <p class="mt-1 text-base font-medium text-gray-900">
                      {{ \Illuminate\Support\Str::limit($question->title ?? $question->question, 90) }}
                    </p>
                    <div class="mt-2 text-sm text-gray-600">
                      {{ $question->quizChoices->count() }} choix - {{ $correctCount }} bonne reponse
                    </div>
                  </li>
                @endforeach
              </ul>
              @if ($questionCount > 4)
                <p class="px-6 pb-4 text-sm text-gray-500">
                  ... et {{ $questionCount - 4 }} question(s) supplementaires sur la page de gestion.
                </p>
              @endif
            @else
              <div class="px-6 py-8 text-sm text-gray-600">
                Aucune question n a encore ete ajoutee. Commencez par en creer une.
              </div>
            @endif
          </article>
        </section>

        <aside class="space-y-6">
          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Etat du quiz</h3>
            <div class="mt-4">
              <div class="h-2 w-full rounded-full bg-gray-100">
                <div class="h-2 rounded-full bg-indigo-500" style="width: {{ $completionRate }}%;"></div>
              </div>
              <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                {{ $completedSteps }} / {{ $totalSteps }} etape(s) terminee(s)
              </p>
            </div>
            <dl class="mt-5 space-y-3 text-sm text-gray-600">
              <div class="flex items-center justify-between">
                <dt>Questions pretes</dt>
                <dd class="font-medium text-gray-900">
                  {{ $hasQuestions ? 'Oui' : 'Non' }}
                </dd>
              </div>
              <div class="flex items-center justify-between">
                <dt>Reponse correcte definie</dt>
                <dd class="font-medium text-gray-900">
                  {{ $questionsMissingCorrect === 0 ? 'Oui' : 'A verifier' }}
                </dd>
              </div>
              <div class="flex items-center justify-between">
                <dt>Parametres</dt>
                <dd class="font-medium text-gray-900">
                  {{ $hasSettings ? 'Configures' : 'A completer' }}
                </dd>
              </div>
            </dl>
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Liens utiles</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-700">
              <li>
                <a href="{{ $editQuizOverviewRoute }}" class="inline-flex items-center gap-2 {{ $currentAction === 'overview' ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }}">
                  <span class="h-2 w-2 rounded-full {{ $currentAction === 'overview' ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
                  Vue generale
                </a>
              </li>
              <li>
                <a href="{{ $editQuizTitleRoute }}" class="inline-flex items-center gap-2 {{ $currentAction === 'title' ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }}">
                  <span class="h-2 w-2 rounded-full {{ $currentAction === 'title' ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
                  Changer le titre
                </a>
              </li>
              <li>
                <a href="{{ $editQuizDescriptionRoute }}" class="inline-flex items-center gap-2 {{ $currentAction === 'description' ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }}">
                  <span class="h-2 w-2 rounded-full {{ $currentAction === 'description' ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
                  Changer la description
                </a>
              </li>
              <li>
                <a href="{{ $editQuizSettingsRoute }}" class="inline-flex items-center gap-2 {{ $currentAction === 'settings' ? 'text-indigo-600 font-semibold' : 'hover:text-indigo-600' }}">
                  <span class="h-2 w-2 rounded-full {{ $currentAction === 'settings' ? 'bg-indigo-500' : 'bg-gray-300' }}"></span>
                  Parametres
                </a>
              </li>
              <li>
                <a href="{{ $manageQuestionsRoute }}" class="inline-flex items-center gap-2 hover:text-indigo-600">
                  <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                  Gerer les questions
                </a>
              </li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </div>
</x-app-layout>
