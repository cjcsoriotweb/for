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
                <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                  class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-indigo-200 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  Gerer les chapitres
                </a>
                <button type="button"
                  class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-indigo-200 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  onclick="toggleTeamsModal(true)">
                  Voir les equipes ({{ $teams->count() }})
                </button>
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
            <a href="{{ route('formateur.formation.edit', $formation) }}"
              class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition hover:border-indigo-200 hover:shadow-sm">
              <div class="flex items-start gap-4">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Modifier les informations</h3>
                  <p class="mt-1 text-sm text-slate-600">
                    Actualisez le titre, la description et le visuel.
                  </p>
                </div>
              </div>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600">
                Ouvrir
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </a>

            <a href="{{ route('formateur.formation.pricing.edit', $formation) }}"
              class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition hover:border-indigo-200 hover:shadow-sm">
              <div class="flex items-start gap-4">
                <span
                  class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Configurer la tarification</h3>
                  <p class="mt-1 text-sm text-slate-600">
                    Definissez le montant facture ou laissez la formation gratuite.
                  </p>
                </div>
              </div>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600">
                Ouvrir
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </a>

            <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
              class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition hover:border-indigo-200 hover:shadow-sm">
              <div class="flex items-start gap-4">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Gerer les chapitres</h3>
                  <p class="mt-1 text-sm text-slate-600">
                    {{ $chapters->count() }} chapitre{{ $chapters->count() === 1 ? '' : 's' }} organises dans la
                    formation.
                  </p>
                </div>
              </div>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600">
                Ouvrir
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </a>

            <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
              class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 transition hover:border-indigo-200 hover:shadow-sm">
              <div class="flex items-start gap-4">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </span>
                <div>
                  <h3 class="text-base font-semibold text-slate-900">Quiz d'entree</h3>
                  <p class="mt-1 text-sm text-slate-600">
                    {{ $entryQuiz ? 'Quiz configure avec ' . $entryQuiz->quizQuestions->count() . ' question(s).' : 'Aucun quiz configure pour l instant.' }}
                  </p>
                </div>
              </div>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600">
                {{ $entryQuiz ? 'Gerer le quiz' : 'Creer un quiz' }}
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </a>
          </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg font-semibold text-slate-900">Equipes rattachees</h2>
                <p class="text-sm text-slate-500">
                  Identifiez les equipes qui exploitent cette formation.
                </p>
              </div>
              <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-600">
                {{ $teams->count() }} equipe{{ $teams->count() === 1 ? '' : 's' }}
              </span>
            </div>

            <ul class="mt-5 space-y-3">
              @forelse($teams->take(4) as $team)
                <li class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
                  <span class="text-sm font-medium text-slate-800">{{ $team->name }}</span>
                  <span class="text-xs text-slate-500">Equipe</span>
                </li>
              @empty
                <li class="rounded-lg border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
                  Aucune equipe rattachee pour le moment.
                </li>
              @endforelse
            </ul>

            @if($teams->count() > 4)
              <p class="mt-3 text-xs text-slate-500">
                ... et {{ $teams->count() - 4 }} equipe{{ $teams->count() - 4 === 1 ? '' : 's' }} supplementaire{{ $teams->count() - 4 === 1 ? '' : 's' }}.
              </p>
            @endif

            <div class="mt-5">
              <button type="button"
                class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-indigo-200 hover:text-indigo-700"
                onclick="toggleTeamsModal(true)">
                Voir toutes les equipes
                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

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
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <div id="teamsModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4 py-6 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <div>
          <h3 class="text-lg font-semibold text-slate-900">Equipes rattachees</h3>
          <p class="text-sm text-slate-500">
            Liste complete des equipes qui utilisent cette formation.
          </p>
        </div>
        <button type="button" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
          data-close>
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="max-h-96 overflow-y-auto px-6 py-5">
        <ul class="space-y-3">
          @forelse($teams as $team)
            <li class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-slate-900">{{ $team->name }}</p>
                <span class="text-xs text-slate-500">Equipe rattachee</span>
              </div>
              <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                ID #{{ $team->id }}
              </span>
            </li>
          @empty
            <li class="rounded-lg border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
              Aucune equipe rattachee pour le moment.
            </li>
          @endforelse
        </ul>
      </div>

      <div class="border-t border-slate-200 px-6 py-4 text-right">
        <button type="button"
          class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-indigo-200 hover:text-indigo-700"
          data-close>
          Fermer
        </button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('teamsModal');

      window.toggleTeamsModal = (show) => {
        if (!modal) {
          return;
        }

        if (show) {
          modal.classList.remove('hidden');
        } else {
          modal.classList.add('hidden');
        }

        document.body.classList.toggle('overflow-hidden', show);
      };

      if (modal) {
        modal.addEventListener('click', (event) => {
          if (event.target === modal) {
            toggleTeamsModal(false);
          }
        });

        modal.querySelectorAll('[data-close]').forEach((button) => {
          button.addEventListener('click', () => toggleTeamsModal(false));
        });

        document.addEventListener('keydown', (event) => {
          if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            toggleTeamsModal(false);
          }
        });
      }
    });
  </script>
</x-app-layout>
