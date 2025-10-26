<x-organisateur-layout :team="$team">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <nav class="mb-4 flex" aria-label="Fil d'Ariane">
        <ol class="flex items-center space-x-4">
          <li>
            <a href="{{ route('organisateur.index', $team) }}"
              class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
              Formations
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <span class="ml-4 text-gray-500 dark:text-gray-400">{{ $formation->title }}</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Eleves inscrits</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $formation->title }} - {{ $formation->description }}</p>
        </div>

        <a href="{{ route('organisateur.index', $team) }}"
          class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour aux formations
        </a>
      </div>
    </div>

    {{-- Statistiques --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
      <div class="rounded-lg bg-white p-5 shadow dark:bg-gray-800">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
              </path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total eleves</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg bg-white p-5 shadow dark:bg-gray-800">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terminees</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg bg-white p-5 shadow dark:bg-gray-800">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En cours</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['in_progress'] }}</p>
          </div>
        </div>
      </div>

      <div class="rounded-lg bg-white p-5 shadow dark:bg-gray-800">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6v6h4"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Temps cumule</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $stats['time_hours'] }}h {{ $stats['time_minutes'] }}min</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Filtres --}}
    <div class="mb-8 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
      <form method="GET" class="grid gap-4 md:grid-cols-3">
        <div>
          <label for="search" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Recherche</label>
          <input id="search" name="search" value="{{ $search }}"
            class="block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
            placeholder="Nom ou email">
        </div>

        <div>
          <label for="status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
          <select id="status" name="status"
            class="block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            <option value="">Tous les statuts</option>
            <option value="completed" @selected($statusFilter === 'completed')>Terminee</option>
            <option value="in_progress" @selected($statusFilter === 'in_progress')>En cours</option>
            <option value="enrolled" @selected($statusFilter === 'enrolled')>Inscrit</option>
          </select>
        </div>

        <div class="flex items-end gap-3">
          <button type="submit"
            class="inline-flex w-full items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 md:w-auto">
            Appliquer
          </button>
          @if($search || $statusFilter)
          <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
            class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 md:w-auto">
            Reinitialiser
          </a>
          @endif
        </div>
      </form>
    </div>

    {{-- Liste des élèves --}}
    @if($studentSummaries->count() > 0)
    <div class="space-y-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Liste des eleves ({{ $studentSummaries->count() }})</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Progression, temps passe et acces rapide au rapport detaille.</p>
      </div>

      <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($studentSummaries as $summary)
        <article class="flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold uppercase text-blue-600 dark:bg-blue-900 dark:text-blue-200">
                {{ $summary->initials }}
              </span>

              <div>
                <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $summary->student->name }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $summary->student->email }}</p>
              </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
              <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium {{ $summary->status_classes }}">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"></path>
                </svg>
                {{ $summary->status_label }}
              </span>

              @if($summary->score_percent)
              <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 00-2 2v9.586A2 2 0 003.414 17L7 13.414a2 2 0 012.828 0L13.414 17A2 2 0 0015 17V5a2 2 0 00-2-2H4z" />
                </svg>
                {{ $summary->score_percent }}%
              </span>
              @endif
            </div>

            <div class="space-y-3">
              <div>
                <div class="mb-1 flex justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
                  <span>Progression</span>
                  <span>{{ $summary->progress_percent }}%</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                  <div class="h-2 bg-blue-500 dark:bg-blue-400" style="width: {{ $summary->progress_percent }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                  {{ $summary->completed_lessons }} / {{ $summary->progress_base }} lecons terminees
                </p>
              </div>

              <dl class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <div class="flex items-center justify-between">
                  <dt class="font-medium text-gray-500 dark:text-gray-400">Inscrit</dt>
                  <dd>{{ $summary->enrolled_at ? $summary->enrolled_at->format('d/m/Y @ H:i') : 'Non renseigne' }}</dd>
                </div>

                @if($summary->last_seen_at)
                <div class="flex items-center justify-between">
                  <dt class="font-medium text-gray-500 dark:text-gray-400">Derniere connexion</dt>
                  <dd>{{ $summary->last_seen_at->format('d/m/Y @ H:i') }}</dd>
                </div>
                @endif

                @if($summary->completed_at)
                <div class="flex items-center justify-between">
                  <dt class="font-medium text-gray-500 dark:text-gray-400">Terminee</dt>
                  <dd>{{ $summary->completed_at->format('d/m/Y @ H:i') }}</dd>
                </div>
                @endif
              </dl>
            </div>
          </div>

          <div class="mt-4 border-t border-dashed border-gray-200 pt-4 dark:border-gray-700">
            @if($summary->has_time)
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
              <div>
                <p class="font-medium text-gray-500 dark:text-gray-400">Temps passe</p>
                <p>{{ $summary->total_hours }}h {{ $summary->total_minutes }}min</p>
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-500 dark:text-gray-400">Lecons consultees</p>
                <p>{{ $summary->lesson_count }}</p>
              </div>
            </div>
            @else
            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune activite enregistree pour le moment.</p>
            @endif

            <a href="{{ route('organisateur.formations.students.report', [$team, $formation, $summary->student]) }}"
              class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Rapport detaille
            </a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
    @else
    <div class="py-12 text-center">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
        </path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun eleve</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aucun eleve n'est encore inscrit a cette formation.</p>
    </div>
    @endif
  </div>
</x-organisateur-layout>
