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
    $totalChoices = 0;
    $questionsMissingCorrect = 0;

    foreach ($questions as $question) {
        $choicesCount = $question->quizChoices->count();
        $totalChoices += $choicesCount;
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
            'label' => 'Titre engageant',
            'description' => 'Un titre clair donne le ton du quiz pour vos participants.',
            'done' => $hasTitle,
            'route' => $editQuizTitleRoute,
        ],
        [
            'label' => 'Description utile',
            'description' => 'Contextualisez le quiz pour expliquer son objectif.',
            'done' => $hasDescription,
            'route' => $editQuizDescriptionRoute,
        ],
        [
            'label' => 'Parametres adaptes',
            'description' => 'Controlez les tentatives et le seuil de reussite si besoin.',
            'done' => $hasSettings,
            'route' => $editQuizSettingsRoute,
        ],
        [
            'label' => 'Questions pretes',
            'description' => 'Ajoutez des questions et marquez la bonne reponse.',
            'done' => $hasQuestions && $questionsMissingCorrect === 0,
            'route' => $manageQuestionsRoute,
        ],
    ];

    $completedSteps = 0;
    foreach ($checklist as $step) {
        if ($step['done']) {
            $completedSteps++;
        }
    }

    $totalSteps = max(count($checklist), 1);
    $completionRate = (int) round(($completedSteps / $totalSteps) * 100);
    $nextStep = null;
    foreach ($checklist as $step) {
        if (! $step['done']) {
            $nextStep = $step;
            break;
        }
    }

    $tips = [];
    if (! $hasQuestions) {
        $tips[] = 'Ajoutez votre premiere question pour donner vie au quiz.';
    }
    if ($questionsMissingCorrect > 0) {
        $tips[] = $questionsMissingCorrect.' question(s) n\'ont pas encore de reponse correcte definie.';
    }
    if (! $hasDescription) {
        $tips[] = 'Une description courte rassure les participants sur les attentes.';
    }
  @endphp

  <div class="py-12">
    <div class="mx-auto max-w-6xl space-y-10 sm:px-6 lg:px-8">
      <div>
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
            <li class="text-gray-500">Quiz du cours</li>
          </ol>
        </nav>
      </div>

      <section class="relative overflow-hidden rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 text-white shadow-xl">
        <div class="absolute inset-0 opacity-20 mix-blend-soft-light" style="background-image: radial-gradient(circle at top left, rgba(255,255,255,0.4), transparent 45%), radial-gradient(circle at bottom right, rgba(255,255,255,0.35), transparent 40%);"></div>
        <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
          <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">
              Chapitre {{ $chapter->position }} - Lecon {{ $lesson->position ?? '-' }}
            </p>
            <h1 class="mt-3 text-3xl font-semibold leading-tight md:text-4xl">
              {{ $quiz->title ?: 'Quiz sans titre' }}
            </h1>
            <p class="mt-3 text-sm text-white/80">
              Gérez votre quiz et suivez sa progression.
            </p>
            <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-white/80">
              <span class="rounded-full border border-white/30 px-3 py-1">Formation : {{ $formation->title }}</span>
              <span class="rounded-full border border-white/30 px-3 py-1">Questions : {{ $questionCount }}</span>
              <span class="rounded-full border border-white/30 px-3 py-1">Tentatives max : {{ $quiz->max_attempts ?? 'Illimite' }}</span>
            </div>
          </div>
          <div class="grid w-full max-w-md grid-cols-2 gap-4 rounded-2xl bg-white/15 p-6 text-sm text-white backdrop-blur">
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Questions</p>
              <p class="mt-1 text-2xl font-semibold">{{ $questionCount }}</p>
              <p class="mt-1 text-xs text-white/70">Total de questions publiees</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Reponses</p>
              <p class="mt-1 text-2xl font-semibold">{{ $totalChoices }}</p>
              <p class="mt-1 text-xs text-white/70">Choix proposes au total</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Avancement</p>
              <p class="mt-1 text-2xl font-semibold">{{ $completionRate }}%</p>
              <p class="mt-1 text-xs text-white/70">Checklist completee</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Etat</p>
              <p class="mt-1 text-2xl font-semibold">
                {{ $hasQuestions ? 'Actif' : 'Brouillon' }}
              </p>
              <p class="mt-1 text-xs text-white/70">Pret pour les participants</p>
            </div>
          </div>
        </div>
      </section>

      <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Questions du quiz</h2>
            <p class="text-sm text-gray-600">Aperçu des questions et gestion</p>
          </div>
          <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
            {{ $questionCount }} question(s)
          </span>
        </div>

        @if ($questionCount > 0)
          <div class="mt-5 space-y-4">
            @foreach ($questions->take(4) as $question)
              @php
                $correctCount = $question->quizChoices->where('is_correct', true)->count();
              @endphp
              <article class="rounded-2xl border border-gray-100 bg-gray-50/80 p-4">
                <header class="flex items-start justify-between gap-3">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Question {{ $loop->iteration }}</p>
                    <h3 class="mt-1 text-sm font-semibold text-gray-900">
                      {{ \Illuminate\Support\Str::limit($question->question, 90) }}
                    </h3>
                  </div>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $correctCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $correctCount > 0 ? 'Correcte definie' : 'Correcte manquante' }}
                  </span>
                </header>
                <div class="mt-3 flex flex-wrap gap-3 text-xs text-gray-600">
                  <span class="inline-flex items-center rounded-full bg-white px-3 py-1">
                    {{ $question->quizChoices->count() }} choix
                  </span>
                  <span class="inline-flex items-center rounded-full bg-white px-3 py-1">
                    {{ $correctCount }} correcte(s)
                  </span>
                </div>
              </article>
            @endforeach
            @if ($questionCount > 4)
              <p class="text-center text-xs text-gray-500">
                ... et {{ $questionCount - 4 }} question(s) de plus sur la page dédiée.
              </p>
            @endif
          </div>
        @else
          <div class="mt-5 rounded-2xl border border-dashed border-indigo-200 bg-indigo-50/70 p-6 text-sm text-indigo-800">
            Aucune question ajoutée. Commencez par créer vos premières questions.
          </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
          <a href="{{ $manageQuestionsRoute }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
            Gérer les questions
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </a>
          <a href="{{ $editQuizSettingsRoute }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-200 px-4 py-2 text-sm font-semibold text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50">
            Paramètres du quiz
          </a>
        </div>
      </section>

      <section class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
          <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Actions principales</h2>
                <p class="text-sm text-gray-600">Configurez votre quiz</p>
              </div>
              @if ($nextStep)
                <a href="{{ $nextStep['route'] }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow hover:bg-indigo-700">
                  <span>Prochaine action</span>
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              @endif
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2">
              <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Titre</p>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Titre du quiz</h3>
                  </div>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $hasTitle ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $hasTitle ? 'Pret' : 'A revoir' }}
                  </span>
                </div>
                <p class="mt-3 text-sm text-gray-600">Définissez un titre clair pour votre quiz.</p>
                <a href="{{ $editQuizTitleRoute }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                  Modifier le titre
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                </a>
              </div>

              <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Description</p>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Description</h3>
                  </div>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $hasDescription ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $hasDescription ? 'Pret' : 'A revoir' }}
                  </span>
                </div>
                <p class="mt-3 text-sm text-gray-600">Ajoutez une description pour contextualiser le quiz.</p>
                <a href="{{ $editQuizDescriptionRoute }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                  Enrichir la description
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                </a>
              </div>

              <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Parametres</p>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Paramètres</h3>
                  </div>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $hasSettings ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $hasSettings ? 'Pret' : 'A revoir' }}
                  </span>
                </div>
                <p class="mt-3 text-sm text-gray-600">Configurez les tentatives et le seuil de réussite.</p>
                <a href="{{ $editQuizSettingsRoute }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                  Ajuster les parametres
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                </a>
              </div>

              <div class="rounded-2xl border border-indigo-100 bg-indigo-50/70 p-5">
                <div class="flex items-start justify-between">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Questions</p>
                    <h3 class="mt-2 text-lg font-semibold text-gray-900">Questions</h3>
                  </div>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $hasQuestions ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $hasQuestions ? 'Pret' : 'A revoir' }}
                  </span>
                </div>
                <p class="mt-3 text-sm text-gray-600">Ajoutez des questions et définissez les bonnes réponses.</p>
                <a href="{{ $manageQuestionsRoute }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                  Gerer les questions
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>

        </div>

        <div class="space-y-6">
          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Checklist de qualite</h3>
            <p class="mt-1 text-sm text-gray-600">Verifiez chaque etape pour garantir une experience fluide.</p>
            <div class="mt-5">
              <div class="h-2 w-full rounded-full bg-gray-100">
                <div class="h-2 rounded-full bg-indigo-500 transition-all" style="width: {{ $completionRate }}%;"></div>
              </div>
              <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                {{ $completedSteps }} / {{ $totalSteps }} etape(s) completee(s)
              </p>
            </div>
            <ul class="mt-5 space-y-4 text-sm">
              @foreach ($checklist as $step)
                <li class="flex items-start justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                  <div>
                    <p class="font-semibold text-gray-900">{{ $step['label'] }}</p>
                    <p class="mt-1 text-xs text-gray-600">{{ $step['description'] }}</p>
                  </div>
                  <a href="{{ $step['route'] }}" class="inline-flex items-center gap-1 text-xs font-semibold uppercase {{ $step['done'] ? 'text-emerald-600 hover:text-emerald-700' : 'text-indigo-600 hover:text-indigo-700' }}">
                    {{ $step['done'] ? 'Revoir' : 'Completer' }}
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Conseils rapides</h3>
            @if (count($tips) > 0)
              <ul class="mt-4 space-y-3 text-sm text-gray-700">
                @foreach ($tips as $tip)
                  <li class="flex items-start gap-3 rounded-lg border border-amber-100 bg-amber-50 p-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                    <span>{{ $tip }}</span>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="mt-3 text-sm text-gray-600">
                Vous avez coche toutes les cases. Vous pouvez deja inviter vos apprenants.
              </p>
            @endif
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Raccourcis</h3>
            <p class="mt-1 text-sm text-gray-600">Retrouvez les pages annexes en un clic.</p>
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
                  Parametres du quiz
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
        </div>
      </section>


    </div>
  </div>
</x-app-layout>
