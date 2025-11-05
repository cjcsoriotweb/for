<x-app-layout>
  <div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      @php
        $formation->loadMissing([
            'chapters.lessons',
            'entryQuiz.quizQuestions',
            'teams:id,name',
            'completionDocuments',
        ]);

        $chapters = $formation->chapters;
        $lessonCount = $chapters->sum(fn ($chapter) => $chapter->lessons->count());
        $teams = $formation->teams;
        $entryQuiz = $formation->entryQuiz;
        $documentsCount = $formation->completionDocuments->count();
        $moneyAmount = $formation->money_amount ?? 0;
        $priceLabel = $moneyAmount > 0
            ? number_format($moneyAmount, 0, '', ' ') . ' EUR'
            : 'Gratuite';
      @endphp

      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-6 sm:p-8">
          <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="space-y-6">
              <a href="{{ route('formateur.home') }}"
                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour aux formations
              </a>

              <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                  <span
                    class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 font-semibold text-slate-600">
                    {{ $formation->active ? 'Formation active' : 'Formation desactivee' }}
                  </span>
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                    {{ $priceLabel }}
                  </span>
                </div>

                <h1 class="text-3xl font-bold text-slate-900">
                  {{ $formation->title }}
                </h1>

                <p class="text-base leading-relaxed text-slate-600">
                  {{ $formation->description ?: 'Aucune description disponible pour le moment.' }}
                </p>
              </div>

              <div class="flex flex-wrap gap-3">
                <a href="{{ route('formateur.formation.edit', $formation) }}"
                  class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  Modifier la formation
                </a>
        
       
              </div>
            </div>

            <div class="w-full max-w-sm lg:max-w-xs">
              <div class="overflow-hidden rounded-xl border border-slate-200">
                <img src="{{ $formation->cover_image_url }}" alt="Image de couverture"
                  class="h-48 w-full object-cover" />
              </div>

              <dl class="mt-6 space-y-3 text-sm">
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500">Chapitres</dt>
                  <dd class="font-semibold text-slate-900">{{ $chapters->count() }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500">Lecons</dt>
                  <dd class="font-semibold text-slate-900">{{ $lessonCount }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500">Equipes rattachees</dt>
                  <dd class="font-semibold text-slate-900">{{ $teams->count() }}</dd>
                </div>
                <div class="flex items-center justify-between">
                  <dt class="text-slate-500">Documents de fin</dt>
                  <dd class="font-semibold text-slate-900">{{ $documentsCount }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 space-y-12">
        <section>
          <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <h2 class="text-xl font-semibold text-slate-900">Actions principales</h2>
              <p class="text-sm text-slate-500">
                Acces rapide aux parametres essentiels de la formation.
              </p>
            </div>
          </div>

          <div class="mt-6 grid gap-4 sm:grid-cols-2">
         

            <a href="{{ route('formateur.formation.pricing.edit', $formation) }}"
              class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition hover:border-indigo-200 hover:shadow-sm">
              <div class="flex items-start gap-4">
                <span
                  class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5h6l4 4v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2z" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Centre de gestion</h3>
                  <p class="mt-1 text-sm text-slate-600">
                    Accedez aux outils pour gerer le contenu pedagogique et contacter le support si besoin.
                  </p>
                </div>
              </div>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600">
                Consulter
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </a>


          </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
   

          <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-slate-900">Ressources et extensions</h2>
            <p class="mt-1 text-sm text-slate-500">
              Completez l experience apprenante avec des outils complementaires.
            </p>

            <div class="mt-5 space-y-3">
              <a href="{{ route('formateur.formation.completion-documents.index', $formation) }}"
                class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3 hover:border-indigo-200 hover:text-indigo-700">
                <div>
                  <p class="text-sm font-semibold text-slate-900">Documents de fin de formation</p>
                  <span class="text-xs text-slate-500">Gerer attestations et contenus telechargeables.</span>
                </div>
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>

              <a href="{{ route('formateur.formation.ai.edit', $formation) }}"
                class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3 hover:border-indigo-200 hover:text-indigo-700">
                <div>
                  <p class="text-sm font-semibold text-slate-900">Parametrage IA</p>
                  <span class="text-xs text-slate-500">Selectionnez la categorie et les assistants proposes.</span>
                </div>
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>

              <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3 hover:border-indigo-200 hover:text-indigo-700">
                <div>
                  <p class="text-sm font-semibold text-slate-900">Questions du quiz d'entree</p>
                  <span class="text-xs text-slate-500">Affinez les questions avant l acces a la formation.</span>
                </div>
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>

                    <a href="{{ route('formateur.formation.teams.index', $formation) }}"
                class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3 hover:border-indigo-200 hover:text-indigo-700">
                Voir toutes les equipes
                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </a>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

</x-app-layout>
