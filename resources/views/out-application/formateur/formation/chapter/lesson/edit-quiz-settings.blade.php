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
    $currentAction = 'settings';
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

    $navigationItems = [
        [
            'label' => 'Vue generale',
            'description' => 'Retour a la synthese du quiz.',
            'action' => 'overview',
            'route' => $editQuizOverviewRoute,
            'done' => null,
        ],
        [
            'label' => 'Titre',
            'description' => 'Renommer le quiz pour refleter le chapitre.',
            'action' => 'title',
            'route' => $editQuizTitleRoute,
            'done' => $hasTitle,
        ],
        [
            'label' => 'Description',
            'description' => 'Clarifier le contexte et les attentes.',
            'action' => 'description',
            'route' => $editQuizDescriptionRoute,
            'done' => $hasDescription,
        ],
        [
            'label' => 'Parametres',
            'description' => 'Determiner les tentatives et les regles.',
            'action' => 'settings',
            'route' => $editQuizSettingsRoute,
            'done' => $hasSettings,
        ],
        [
            'label' => 'Questions',
            'description' => 'Ajouter des questions et reponses.',
            'action' => 'questions',
            'route' => $manageQuestionsRoute,
            'done' => $hasQuestions && $questionsMissingCorrect === 0,
        ],
    ];

    $settingsTips = [
        '1 tentative suffit pour une evaluation certificative.',
        '2 ou 3 tentatives encouragent la revision accompagnee.',
        'Tentatives illimitees pour un mode entrainement et auto-apprentissage.',
    ];

    $estimatedDurationFallback = $quiz->estimated_duration_minutes;
    if (! $estimatedDurationFallback) {
        $estimatedDurationFallback = $questionCount > 0 ? max($questionCount * 2, 5) : 5;
    }
    $defaultEstimatedDuration = old('quiz_estimated_duration', $estimatedDurationFallback);
  @endphp

  <div class="py-12">
    <div class="mx-auto max-w-4xl space-y-8 sm:px-6 lg:px-8">
      <nav class="flex text-sm text-white/80" aria-label="Fil d Ariane">
        <ol class="inline-flex items-center gap-2">
          <li>
            <a href="{{ route('formateur.formation.show', $formation) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
              {{ $formation->title }}
            </a>
          </li>
          <li class="text-gray-400">/</li>
          <li>
            <a href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
              Chapitre {{ $chapter->position }}
            </a>
          </li>
          <li class="text-gray-400">/</li>
          <li class="text-gray-500">Parametres du quiz</li>
        </ol>
      </nav>

      <section class="relative overflow-hidden rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-6 text-white shadow-xl">
        <div class="absolute inset-0 opacity-20 mix-blend-soft-light" style="background-image: radial-gradient(circle at top left, rgba(255,255,255,0.4), transparent 45%), radial-gradient(circle at bottom right, rgba(255,255,255,0.35), transparent 40%);"></div>
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
          <div class="max-w-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">
              Chapitre {{ $chapter->position }} - Lecon {{ $lesson->position ?? '-' }}
            </p>
            <h1 class="mt-3 text-2xl font-semibold leading-tight md:text-3xl">
              Ajuster les parametres
            </h1>
            <p class="mt-2 text-sm text-white/80">
              Choisissez le nombre de tentatives autorisees pour aligner le quiz avec votre pedagogie.
            </p>
          </div>
          <div class="grid w-full max-w-xs gap-3 text-sm text-white">
            <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
              <p class="text-xs uppercase tracking-wide text-white/70">Tentatives</p>
              <p class="mt-1 text-base font-semibold">
                {{ $quiz->max_attempts ? $quiz->max_attempts.' autorisee(s)' : 'Illimite' }}
              </p>
            </div>
            <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
              <p class="text-xs uppercase tracking-wide text-white/70">Questions configurees</p>
              <p class="mt-1 text-base font-semibold">{{ $questionCount }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px]">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
          <div class="border-b border-gray-100 px-6 py-5">
            <h2 class="text-xl font-semibold text-gray-900">Controler le rythme</h2>
            <p class="mt-1 text-sm text-gray-600">
              Fixez le nombre de tentatives en fonction de l importance du quiz.
            </p>
          </div>
          <div class="space-y-6 px-6 py-6">
            <div class="rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm text-indigo-900">
              <p>
                Laissez le champ vide pour un mode libre. Limitez les tentatives si vous validez des competences critiques.
              </p>
            </div>

            <form method="POST" action="{{ route('formateur.formation.chapter.lesson.quiz.update', [$formation, $chapter, $lesson]) }}" class="space-y-6">
              @csrf
              @method('PUT')
              <input type="hidden" name="quiz_title" value="{{ old('quiz_title', $quiz->title) }}">
              <textarea name="quiz_description" class="hidden" aria-hidden="true">{{ old('quiz_description', $quiz->description) }}</textarea>

              <div>
                <label for="max_attempts" class="block text-sm font-semibold text-gray-900">Tentatives maximum</label>
                <p class="mt-1 text-xs text-gray-500">Entre 1 et 10, ou laissez vide pour autoriser un nombre illimite.</p>

                <div class="mt-3 flex items-center gap-4">
                  <input
                    type="number"
                    id="max_attempts"
                    name="max_attempts"
                    min="1"
                    max="10"
                    value="{{ old('max_attempts', $quiz->max_attempts) }}"
                    class="w-28 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200 @error('max_attempts') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                    placeholder="Illimite"
                  />
                  <div class="text-xs text-gray-500">
                    <p>Repere rapide :</p>
                    <ul class="mt-1 space-y-1">
                      <li>- 1 tentative = evaluation finale</li>
                      <li>- 2 a 3 tentatives = apprentissage accompagne</li>
                      <li>- Vide = entrainement libre</li>
                    </ul>
                  </div>
                </div>
                @error('max_attempts')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="quiz_estimated_duration" class="block text-sm font-semibold text-gray-900">Temps estimé *</label>
                <p class="mt-1 text-xs text-gray-500">Durée moyenne nécessaire pour compléter le quiz (1 à 1440 minutes).</p>

                <div class="mt-3 flex items-center gap-4">
                  <input
                    type="number"
                    id="quiz_estimated_duration"
                    name="quiz_estimated_duration"
                    min="1"
                    max="1440"
                    value="{{ $defaultEstimatedDuration }}"
                    class="w-32 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200 @error('quiz_estimated_duration') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                    placeholder="Ex: 10"
                    required
                  />
                  <p class="text-xs text-gray-500">
                    Ce champ alimente le temps total de la formation.
                  </p>
                </div>
                @error('quiz_estimated_duration')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="flex flex-wrap items-center justify-between gap-4">
                <a href="{{ $editQuizOverviewRoute }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-800">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Retour a la vue generale
                </a>
                <button
                  type="submit"
                  class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                  Enregistrer les parametres
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="space-y-5">
          <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Navigation rapide</h3>
            <ul class="mt-4 space-y-3 text-sm text-gray-700">
              @foreach ($navigationItems as $item)
                @php
                  $isActive = $currentAction === $item['action'];
                  $isDone = $item['done'];
                @endphp
                <li>
                  <a
                    href="{{ $item['route'] }}"
                    class="group flex w-full items-start justify-between gap-3 rounded-xl border px-4 py-3 transition {{ $isActive ? 'border-indigo-200 bg-indigo-50 text-indigo-800 shadow' : 'border-gray-100 bg-gray-50 hover:border-indigo-200 hover:bg-white hover:text-indigo-700' }}"
                  >
                    <div>
                      <p class="font-semibold">{{ $item['label'] }}</p>
                      <p class="mt-1 text-xs text-gray-500 group-hover:text-current">
                        {{ $item['description'] }}
                      </p>
                    </div>
                    @if ($isActive)
                      <span class="mt-1 inline-flex items-center rounded-full bg-indigo-600 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-white">En cours</span>
                    @elseif (! is_null($isDone))
                      <span class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide {{ $isDone ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $isDone ? 'Pret' : 'A finaliser' }}
                      </span>
                    @else
                      <span class="mt-1 inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-gray-600 group-hover:bg-indigo-100 group-hover:text-indigo-700">
                        Ouvrir
                      </span>
                    @endif
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

          <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5 text-sm text-indigo-900 shadow-sm">
            <h3 class="text-base font-semibold text-indigo-900">Conseils de parametrage</h3>
            <ul class="mt-3 space-y-2">
              @foreach ($settingsTips as $tip)
                <li class="flex items-start gap-3">
                  <span class="mt-1 h-2 w-2 rounded-full bg-indigo-500"></span>
                  <span>{{ $tip }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </section>
    </div>
  </div>
</x-app-layout>
