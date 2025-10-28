<div class="space-y-10">
  <header class="bg-white border border-gray-200 rounded-xl shadow-sm">
    <div class="p-8 space-y-5">
      <div class="space-y-2">
        <h1 class="text-2xl font-semibold text-gray-900">
          Gestion des formations
        </h1>
        <p class="text-sm text-gray-600">
          Supervisez l'ensemble de vos contenus et ajustez leur visibilité en quelques clics.
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-4">
        <a
          href="{{ route('formateur.home') }}"
          class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl shadow-sm transition"
        >
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
          </svg>
          Créer / Editer des formations
        </a>

        <div class="inline-flex items-center gap-3 px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-lg">
          <span class="font-medium text-gray-900">
            Formations actives
          </span>
          <span
            class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
            {{ $formations->where('is_visible', true)->count() }} / {{ $formations->count() }}
          </span>
        </div>
      </div>
    </div>
  </header>

  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold text-gray-900">
          Liste des formations
        </h2>
        <p class="text-sm text-gray-600">
          Retrouvez vos parcours et modifiez leur statut de publication.
        </p>
      </div>
    </div>

    @forelse($formations as $formation)
    <article
      class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
      <div class="space-y-3">
        <div class="flex items-center gap-3">
          <span
            class="inline-flex items-center justify-center size-8 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">
            {{ $loop->iteration }}
          </span>
          <h3 class="text-lg font-semibold text-gray-900">
            {{ $formation->title }}
          </h3>
        </div>

        @if($formation->description)
        <p class="text-sm leading-relaxed text-gray-600">
          {{ $formation->description }}
        </p>
        @else
        <p class="text-sm italic text-gray-500">
          Aucune description renseignée pour le moment.
        </p>
        @endif

        <a
          href="{{ route('application.admin.formations.revenue', ['team' => $team, 'formation' => $formation]) }}"
          class="inline-flex items-center gap-3 rounded-lg border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 transition hover:-translate-y-0.5 hover:bg-purple-100 hover:shadow"
        >
          <span class="material-symbols-outlined text-lg text-purple-500">token</span>
          <span class="flex flex-col leading-tight">
            <span class="text-xs font-medium text-purple-500 uppercase tracking-widest">
              Revenu {{ $currentMonthLabel }}
            </span>
            <span class="text-base font-semibold text-purple-700">
              {{ number_format($monthlyRevenue->get($formation->id, 0), 0, ',', ' ') }} jetons
            </span>
          </span>
          <span class="material-symbols-outlined text-base text-purple-400">arrow_outward</span>
        </a>
      </div>

      <div class="flex items-center gap-3 md:self-stretch md:flex-col md:justify-between">
        <span
          class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $formation->is_visible ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
          {{ $formation->is_visible ? 'Visible' : 'Masquée' }}
        </span>

        <form method="POST"
          action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}">
          @csrf
          <input type="hidden" name="team_id" value="{{ $team->id }}" />
          <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
          @if($formation->is_visible)
          <button type="submit" name="enabled" value="0"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition">
            {{ __('Désactiver') }}
          </button>
          @else
          <button type="submit" name="enabled" value="1"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
            {{ __('Activer') }}
          </button>
          @endif
        </form>
      </div>
    </article>
    @empty
    <div
      class="flex flex-col items-center justify-center gap-3 py-16 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor"
        class="size-12 text-purple-300">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="m11.25 3.75.041.02a8.99 8.99 0 0 1 4.478 4.478l.02.041a2.25 2.25 0 0 0 1.116 1.116l.041.02a8.99 8.99 0 0 1 4.478 4.478l.02.041a2.25 2.25 0 0 1-2.433 3.183 48.423 48.423 0 0 0-6.149-1.23 48.428 48.428 0 0 0-4.946-.37c-.552 0-1.102.012-1.65.037A2.25 2.25 0 0 1 3 16.5v-.75a3 3 0 0 1 3-3h.75A2.25 2.25 0 0 0 9 10.5v-.75a3 3 0 0 1 3-3h-.75A2.25 2.25 0 0 1 9 4.5V3.75a2.25 2.25 0 0 1 2.25-2.25z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75m-3 3h3.75" />
      </svg>
      <div class="text-center space-y-1">
        <p class="text-lg font-semibold text-gray-900">
          Aucune formation pour le moment
        </p>
        <p class="text-sm text-gray-600">
          Lancez-vous en créant votre première formation depuis le raccourci ci-dessus.
        </p>
      </div>
    </div>
    @endforelse
  </section>
</div>
