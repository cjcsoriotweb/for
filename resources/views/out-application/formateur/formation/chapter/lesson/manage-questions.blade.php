
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
    $questionCount = $questions->count();
    $choiceCount = 0;
    $questionsMissingCorrect = 0;
    $questionsWithFewChoices = 0;

    foreach ($questions as $question) {
        $choices = $question->quizChoices;
        $choiceCount += $choices->count();

        if ($choices->where('is_correct', true)->count() === 0) {
            $questionsMissingCorrect++;
        }

        if ($choices->count() < 2) {
            $questionsWithFewChoices++;
        }
    }

    $checklist = [
        [
            'label' => 'Au moins une question',
            'description' => 'Ajoutez les questions clefs du chapitre.',
            'done' => $questionCount > 0,
        ],
        [
            'label' => 'Reponse correcte par question',
            'description' => 'Chaque question doit proposer au moins une bonne reponse.',
            'done' => $questionCount > 0 && $questionsMissingCorrect === 0,
        ],
        [
            'label' => 'Deux choix minimum',
            'description' => 'Offrez au moins deux options par question.',
            'done' => $questionCount > 0 && $questionsWithFewChoices === 0,
        ],
    ];

    $completedSteps = 0;
    foreach ($checklist as $item) {
        if ($item['done']) {
            $completedSteps++;
        }
    }

    $totalSteps = max(count($checklist), 1);
    $completionRate = (int) round(($completedSteps / $totalSteps) * 100);

    $baseQuestionsUrl = url("/formateur/formation/{$formation->id}/chapitre/{$chapter->id}/lesson/{$lesson->id}/quiz/{$quiz->id}/questions");
    $storeQuestionsUrl = route('formateur.formation.chapter.lesson.quiz.questions.store', [$formation, $chapter, $lesson, $quiz]);
    $testQuizUrl = route('formateur.formation.chapter.lesson.quiz.test', [$formation, $chapter, $lesson, $quiz]);
    $backToQuizUrl = route('formateur.formation.chapter.lesson.quiz.edit', [$formation, $chapter, $lesson]);
  @endphp
  <div class="py-12">
    <div class="mx-auto max-w-6xl space-y-10 sm:px-6 lg:px-8">
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
          <li>
            <a href="{{ $backToQuizUrl }}" class="font-medium text-indigo-600 hover:text-indigo-700">
              {{ $lesson->title ?? 'Lecon' }}
            </a>
          </li>
          <li class="text-gray-400">/</li>
          <li class="text-gray-500">Questions du quiz</li>
        </ol>
      </nav>

      <section class="relative overflow-hidden rounded-3xl border border-indigo-100 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 p-8 text-white shadow-xl">
        <div class="absolute inset-0 opacity-20 mix-blend-soft-light" style="background-image: radial-gradient(circle at top left, rgba(255,255,255,0.4), transparent 45%), radial-gradient(circle at bottom right, rgba(255,255,255,0.35), transparent 40%);"></div>
        <div class="relative z-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
          <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/70">
              Chapitre {{ $chapter->position }} - Lecon {{ $lesson->position ?? '-' }}
            </p>
            <h1 class="mt-3 text-3xl font-semibold leading-tight md:text-4xl">
              Composer les questions du quiz
            </h1>
            <p class="mt-3 text-sm text-white/80">
              Equilibrez evaluation et pedagogie : definissez les questions, les reponses et testez le quiz avant publication.
            </p>
            <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-white/80">
              <span class="rounded-full border border-white/30 px-3 py-1">Quiz : {{ $quiz->title ?: 'Sans titre' }}</span>
              <span class="rounded-full border border-white/30 px-3 py-1">Questions : {{ $questionCount }}</span>
              <span class="rounded-full border border-white/30 px-3 py-1">Reponses : {{ $choiceCount }}</span>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
              <a
                href="{{ $testQuizUrl }}"
                class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white hover:text-indigo-600"
              >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M15.5 5.5l5 5-5 5"></path>
                  <path d="M3.5 12h16.5"></path>
                </svg>
                Tester le quiz
              </a>
            </div>
          </div>
          <div class="grid w-full max-w-md grid-cols-2 gap-4 rounded-2xl bg-white/15 p-6 text-sm text-white backdrop-blur">
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Questions</p>
              <p class="mt-1 text-2xl font-semibold">{{ $questionCount }}</p>
              <p class="mt-1 text-xs text-white/65">Total ajoute</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Reponses</p>
              <p class="mt-1 text-2xl font-semibold">{{ $choiceCount }}</p>
              <p class="mt-1 text-xs text-white/65">Choix proposes</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Questions a verifier</p>
              <p class="mt-1 text-2xl font-semibold">{{ $questionsMissingCorrect }}</p>
              <p class="mt-1 text-xs text-white/65">Sans bonne reponse</p>
            </div>
            <div>
              <p class="text-xs uppercase tracking-wide text-white/70">Progression</p>
              <p class="mt-1 text-2xl font-semibold">{{ $completionRate }}%</p>
              <p class="mt-1 text-xs text-white/65">Checklist completee</p>
            </div>
          </div>
        </div>
      </section>

      <section class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_1fr]">
        <div class="space-y-6">
          <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-6 py-5">
              <h2 class="text-xl font-semibold text-gray-900">Nouvelle question</h2>
              <p class="mt-1 text-sm text-gray-600">
                Ajoutez une question au quiz et indiquez la bonne reponse.
              </p>
            </div>
            @php
              $oldChoicesData = null;
              $oldChoicesRaw = old('choices');
              if ($oldChoicesRaw) {
                  $decoded = json_decode($oldChoicesRaw, true);
                  if (is_array($decoded)) {
                      $oldChoicesData = $decoded;
                  }
              }
              $oldQuestionType = old('question_type', 'multiple_choice');
            @endphp
            <form method="POST" action="{{ $storeQuestionsUrl }}" class="question-form space-y-6 px-6 py-6" data-form-id="create">
              @csrf
              <input type="hidden" name="choices" class="choices-json">

              <div>
                <label for="create-question" class="block text-sm font-semibold text-gray-900">Texte de la question *</label>
                <textarea
                  id="create-question"
                  name="question_text"
                  rows="3"
                  class="question-text mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                  placeholder="Exemple : Quelle est la capitale de la France ?"
                  required
                >{{ old('question_text') }}</textarea>
              </div>

              <div>
                <span class="block text-sm font-semibold text-gray-900">Type de question *</span>
                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                  <label class="flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-sm text-gray-800">
                    <input type="radio" name="question_type" value="multiple_choice" class="text-indigo-600 focus:ring-indigo-500" {{ $oldQuestionType === 'multiple_choice' ? 'checked' : '' }}>
                    Choix multiples
                  </label>
                  <label class="flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-sm text-gray-800">
                    <input type="radio" name="question_type" value="true_false" class="text-indigo-600 focus:ring-indigo-500" {{ $oldQuestionType === 'true_false' ? 'checked' : '' }}>
                    Vrai ou Faux
                  </label>
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between">
                  <p class="text-sm font-semibold text-gray-900">Reponses *</p>
                  <button type="button" data-add-choice class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                    + Ajouter une reponse
                  </button>
                </div>
                <div data-choice-list class="mt-3 space-y-2">
                  @if ($oldQuestionType === 'true_false')
                    @php
                      $firstCorrect = $oldChoicesData[0]['is_correct'] ?? true;
                      $secondCorrect = $oldChoicesData[1]['is_correct'] ?? false;
                    @endphp
                    <div class="choice-row flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2" data-readonly="true">
                      <input type="radio" name="create_correct" value="0" class="choice-radio text-indigo-600 focus:ring-indigo-500" {{ $firstCorrect ? 'checked' : '' }}>
                      <input type="text" class="choice-input flex-1 rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm" value="Vrai" readonly>
                      <button type="button" class="remove-choice hidden"></button>
                    </div>
                    <div class="choice-row flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2" data-readonly="true">
                      <input type="radio" name="create_correct" value="1" class="choice-radio text-indigo-600 focus:ring-indigo-500" {{ $secondCorrect ? 'checked' : '' }}>
                      <input type="text" class="choice-input flex-1 rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm" value="Faux" readonly>
                      <button type="button" class="remove-choice hidden"></button>
                    </div>
                  @else
                    @php
                      $initialChoices = $oldChoicesData ?: [
                          ['text' => '', 'is_correct' => true],
                          ['text' => '', 'is_correct' => false],
                      ];
                    @endphp
                    @foreach ($initialChoices as $idx => $choice)
                      <div class="choice-row flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2" data-readonly="false">
                        <input type="radio" name="create_correct" value="{{ $idx }}" class="choice-radio text-indigo-600 focus:ring-indigo-500" {{ ($choice['is_correct'] ?? false) ? 'checked' : ($idx === 0 ? 'checked' : '') }}>
                        <input type="text" class="choice-input flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200" placeholder="Reponse {{ $idx + 1 }}" value="{{ $choice['text'] ?? '' }}">
                        <button type="button" class="remove-choice p-1 text-red-600 transition hover:text-red-700">
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </div>
                    @endforeach
                  @endif
                </div>
              </div>

              <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  Ajouter la question
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"></path>
                  </svg>
                </button>
              </div>
            </form>
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Questions du quiz</h2>
                <p class="text-sm text-gray-600">Consultez vos questions et ajustez-les si necessaire.</p>
              </div>
              <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                {{ $questionCount }} question(s)
              </span>
            </div>

            @if ($questionCount > 0)
              <div class="space-y-5 px-6 py-6">
                @foreach ($questions as $index => $question)
                  @php
                    $correctChoices = $question->quizChoices->where('is_correct', true)->count();
                    $isTrueFalse = $question->type === 'true_false';
                  @endphp
                  <article class="rounded-2xl border border-gray-200 bg-gray-50/70 p-5 shadow-sm">
                    <header class="flex items-start justify-between gap-4">
                      <div>
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                          <span class="rounded-full bg-indigo-100 px-2.5 py-0.5 font-semibold text-indigo-700">Question {{ $index + 1 }}</span>
                          <span class="rounded-full px-2.5 py-0.5 font-semibold {{ $isTrueFalse ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $isTrueFalse ? 'Vrai/Faux' : 'QCM' }}
                          </span>
                          <span class="rounded-full bg-white px-2.5 py-0.5 font-semibold text-gray-600">
                            {{ $question->quizChoices->count() }} choix
                          </span>
                          <span class="rounded-full bg-white px-2.5 py-0.5 font-semibold {{ $correctChoices > 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $correctChoices }} correct
                          </span>
                        </div>
                        <p class="mt-3 text-base font-semibold text-gray-900">
                          {{ $question->question }}
                        </p>
                      </div>
                    </header>

                    <ul class="mt-4 space-y-2 text-sm text-gray-700">
                      @foreach ($question->quizChoices as $choice)
                        <li class="flex items-start gap-3 rounded-2xl border border-gray-200 bg-white px-3 py-2">
                          <span class="mt-1 h-2 w-2 rounded-full {{ $choice->is_correct ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                          <span class="{{ $choice->is_correct ? 'font-semibold text-emerald-700' : '' }}">
                            {{ $choice->choice_text }}
                          </span>
                        </li>
                      @endforeach
                    </ul>

                    <details class="mt-4 rounded-xl border border-dashed border-gray-200 bg-white/60 p-4">
                      <summary class="cursor-pointer text-sm font-semibold text-indigo-600">
                        Modifier cette question
                      </summary>
                      <div class="mt-4 space-y-4 border-t border-gray-100 pt-4">
                        <form
                          method="POST"
                          action="{{ route('formateur.formation.chapter.lesson.quiz.questions.update', [$formation, $chapter, $lesson, $quiz, $question]) }}"
                          class="question-form space-y-4"
                          data-form-id="question-{{ $question->id }}"
                        >
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="choices" class="choices-json">

                          <div>
                            <label class="block text-sm font-semibold text-gray-900" for="question-text-{{ $question->id }}">Texte de la question *</label>
                            <textarea
                              id="question-text-{{ $question->id }}"
                              name="question_text"
                              rows="3"
                              class="question-text mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                              required
                            >{{ $question->question }}</textarea>
                          </div>

                          <div>
                            <span class="block text-sm font-semibold text-gray-900">Type de question *</span>
                            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                              <label class="flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-sm text-gray-800">
                                <input type="radio" name="question_type" value="multiple_choice" class="text-indigo-600 focus:ring-indigo-500" {{ $isTrueFalse ? '' : 'checked' }}>
                                Choix multiples
                              </label>
                              <label class="flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50/70 px-4 py-3 text-sm text-gray-800">
                                <input type="radio" name="question_type" value="true_false" class="text-indigo-600 focus:ring-indigo-500" {{ $isTrueFalse ? 'checked' : '' }}>
                                Vrai ou Faux
                              </label>
                            </div>
                          </div>

                          <div>
                            <div class="flex items-center justify-between">
                              <p class="text-sm font-semibold text-gray-900">Reponses *</p>
                              <button type="button" data-add-choice class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                + Ajouter une reponse
                              </button>
                            </div>
                            <div data-choice-list class="mt-3 space-y-2">
                              @foreach ($question->quizChoices as $idx => $choice)
                                <div class="choice-row flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2" data-readonly="{{ $isTrueFalse ? 'true' : 'false' }}">
                                  <input type="radio" name="question-{{ $question->id }}_correct" value="{{ $idx }}" class="choice-radio text-indigo-600 focus:ring-indigo-500" {{ $choice->is_correct ? 'checked' : '' }}>
                                  <input type="text" class="choice-input flex-1 rounded-lg border border-gray-300 {{ $isTrueFalse ? 'bg-gray-100' : '' }} px-3 py-2 text-sm" placeholder="Reponse {{ $idx + 1 }}" value="{{ $choice->choice_text }}" {{ $isTrueFalse ? 'readonly' : '' }}>
                                  <button type="button" class="remove-choice {{ $isTrueFalse ? 'hidden' : '' }} p-1 text-red-600 transition hover:text-red-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                  </button>
                                </div>
                              @endforeach
                            </div>
                          </div>

                          <div class="flex justify-end gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                              Mettre a jour la question
                              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"></path>
                              </svg>
                            </button>
                          </div>
                        </form>

                        <form
                          method="POST"
                          action="{{ route('formateur.formation.chapter.lesson.quiz.questions.delete', [$formation, $chapter, $lesson, $quiz, $question]) }}"
                          onsubmit="return confirm('Supprimer cette question ?');"
                          class="flex justify-end"
                        >
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="rounded-full border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                            Supprimer la question
                          </button>
                        </form>
                      </div>
                    </details>
                  </article>
                @endforeach
              </div>
            @else
              <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-dashed border-indigo-200 text-indigo-500">
                  <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">Aucune question pour le moment</h3>
                <p class="mt-2 text-sm text-gray-600">
                  Utilisez le formulaire ci-dessus pour ajouter votre premiere question.
                </p>
              </div>
            @endif
          </div>
        </div>

        <aside class="space-y-6">
          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Resume du quiz</h3>
            <dl class="mt-4 space-y-3 text-sm text-gray-700">
              <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Titre</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">{{ $quiz->title ?: 'Pas encore defini' }}</dd>
              </div>
              @if ($quiz->description)
                <div>
                  <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Description</dt>
                  <dd class="mt-1 rounded-xl border border-dashed border-gray-200 bg-gray-50 p-3 text-sm leading-relaxed text-gray-700">
                    {{ $quiz->description }}
                  </dd>
                </div>
              @endif
              <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tentatives maximum</dt>
                <dd class="mt-1 text-base font-semibold text-gray-900">
                  {{ $quiz->max_attempts ?? 'Illimite' }}
                </dd>
              </div>
              <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Etat</dt>
                <dd class="mt-1 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $questionCount > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                  {{ $questionCount > 0 ? 'Pret a tester' : 'En construction' }}
                </dd>
              </div>
            </dl>
          </div>

          <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">Checklist de qualite</h3>
            <div class="mt-4">
              <div class="h-2 w-full rounded-full bg-gray-100">
                <div class="h-2 rounded-full bg-indigo-500 transition-all" style="width: {{ $completionRate }}%;"></div>
              </div>
              <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                {{ $completedSteps }} / {{ $totalSteps }} etape(s) completee(s)
              </p>
            </div>
            <ul class="mt-4 space-y-3 text-sm">
              @foreach ($checklist as $item)
                <li class="flex items-start gap-3 rounded-2xl border border-gray-100 bg-gray-50/60 p-4">
                  <span class="mt-1 h-2.5 w-2.5 rounded-full {{ $item['done'] ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                  <div>
                    <p class="font-semibold text-gray-900">{{ $item['label'] }}</p>
                    <p class="mt-1 text-xs text-gray-600">{{ $item['description'] }}</p>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </aside>
      </section>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.question-form').forEach(function (form) {
        setupQuestionForm(form);
      });
    });

    function setupQuestionForm(form) {
      const addButton = form.querySelector('[data-add-choice]');
      if (addButton) {
        addButton.addEventListener('click', function () {
          addChoiceRow(form);
        });
      }

      const radios = form.querySelectorAll('input[name="question_type"]');
      radios.forEach(function (radio) {
        radio.addEventListener('change', function () {
          handleQuestionTypeChange(form);
        });
      });

      attachRemoveHandlers(form);
      handleQuestionTypeChange(form, true);

      form.addEventListener('submit', function (event) {
        if (!prepareChoicesPayload(form)) {
          event.preventDefault();
        }
      });
    }

    function attachRemoveHandlers(form) {
      form.querySelectorAll('.remove-choice').forEach(function (btn) {
        if (!btn.dataset.handlerAttached) {
          btn.dataset.handlerAttached = 'true';
          btn.addEventListener('click', function () {
            const row = btn.closest('.choice-row');
            if (row) {
              removeChoiceRow(form, row);
            }
          });
        }
      });
    }

    function handleQuestionTypeChange(form, initializing = false) {
      const typeInput = form.querySelector('input[name="question_type"]:checked');
      if (!typeInput) {
        return;
      }
      const type = typeInput.value;
      const container = form.querySelector('[data-choice-list]');
      const addButton = form.querySelector('[data-add-choice]');
      if (!container) {
        return;
      }

      if (type === 'true_false') {
        if (addButton) {
          addButton.classList.add('hidden');
        }
        if (!initializing || container.children.length === 0 || container.querySelector('[data-readonly="true"]') === null) {
          container.innerHTML = '';
          addChoiceRow(form, { text: 'Vrai', isCorrect: true, readOnly: true });
          addChoiceRow(form, { text: 'Faux', isCorrect: false, readOnly: true });
        }
      } else {
        if (addButton) {
          addButton.classList.remove('hidden');
        }
        if (container.children.length === 0 || container.querySelector('[data-readonly="true"]')) {
          container.innerHTML = '';
          addChoiceRow(form, { text: '', isCorrect: true, readOnly: false });
          addChoiceRow(form, { text: '', isCorrect: false, readOnly: false });
        }
        refreshChoiceIndexes(form);
      }

      attachRemoveHandlers(form);
    }
    function addChoiceRow(form, options = {}) {
      const container = form.querySelector('[data-choice-list]');
      if (!container) {
        return;
      }
      const row = document.createElement('div');
      row.className = 'choice-row flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2';
      row.setAttribute('data-readonly', options.readOnly ? 'true' : 'false');

      const radio = document.createElement('input');
      radio.type = 'radio';
      radio.name = form.dataset.formId + '_correct';
      radio.className = 'choice-radio text-indigo-600 focus:ring-indigo-500';
      row.appendChild(radio);

      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'choice-input flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200';
      input.value = options.text || '';
      if (options.readOnly) {
        input.readOnly = true;
        input.classList.add('bg-gray-100');
      }
      row.appendChild(input);

      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'remove-choice p-1 text-red-600 transition hover:text-red-700';
      removeBtn.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
      if (options.readOnly) {
        removeBtn.classList.add('hidden');
      }
      row.appendChild(removeBtn);

      container.appendChild(row);

      if (options.isCorrect) {
        radio.checked = true;
      }

      refreshChoiceIndexes(form);
      attachRemoveHandlers(form);
    }

    function removeChoiceRow(form, row) {
      const container = form.querySelector('[data-choice-list]');
      if (!container) {
        return;
      }
      if (container.children.length <= 2) {
        alert('Une question doit avoir au moins deux reponses.');
        return;
      }
      row.remove();
      refreshChoiceIndexes(form);
    }

    function refreshChoiceIndexes(form) {
      const rows = form.querySelectorAll('.choice-row');
      rows.forEach(function (row, index) {
        const radio = row.querySelector('.choice-radio');
        const input = row.querySelector('.choice-input');
        if (radio) {
          radio.value = index;
        }
        if (input && row.getAttribute('data-readonly') !== 'true') {
          input.placeholder = 'Reponse ' + (index + 1);
        }
      });

      const groupName = form.dataset.formId + '_correct';
      const checked = form.querySelector('input[name="' + groupName + '"]:checked');
      if (!checked && rows.length) {
        rows[0].querySelector('.choice-radio').checked = true;
      }
    }

    function prepareChoicesPayload(form) {
      const questionField = form.querySelector('.question-text');
      const questionText = questionField ? questionField.value.trim() : '';
      if (!questionText) {
        alert('Veuillez saisir le texte de la question.');
        return false;
      }

      const rows = form.querySelectorAll('.choice-row');
      if (rows.length < 2) {
        alert('Veuillez ajouter au moins deux reponses.');
        return false;
      }

      const choices = [];
      let hasCorrect = false;
      for (let index = 0; index < rows.length; index++) {
        const row = rows[index];
        const input = row.querySelector('.choice-input');
        const radio = row.querySelector('.choice-radio');
        const text = input ? input.value.trim() : '';
        if (!text) {
          alert('Veuillez saisir le texte de la reponse ' + (index + 1) + '.');
          return false;
        }
        const isCorrect = radio && radio.checked;
        if (isCorrect) {
          hasCorrect = true;
        }
        choices.push({ text: text, is_correct: isCorrect });
      }

      if (!hasCorrect) {
        alert('Veuillez selectionner au moins une reponse correcte.');
        return false;
      }

      const target = form.querySelector('.choices-json');
      if (target) {
        target.value = JSON.stringify(choices);
      }
      return true;
    }
  </script>
</x-app-layout>

