<x-organisateur-layout :team="$team">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <x-organisateur.parts.breadcrumb :team="$team" :formation="$formation" />

      <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <div
              class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                </path>
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Élèves inscrits</h1>
              <div class="flex items-center gap-2 mt-1">
                <span
                  class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                  <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"></path>
                  </svg>
                  {{ $stats['total'] }} élèves
                </span>
              </div>
            </div>
          </div>
          <p class="text-lg text-gray-600 dark:text-gray-400">{{ $formation->title }}</p>
          <p class="text-gray-500 dark:text-gray-500">{{ $formation->description }}</p>
        </div>

        <x-organisateur.parts.action-buttons :buttons="[
          ['type' => 'back', 'url' => route('organisateur.index', $team), 'text' => 'Retour aux formations']
        ]" />
      </div>
    </div>

    {{-- Statistiques --}}
    <x-organisateur.parts.stats-cards :stats="$stats" type="students" :team="$team" :formation="$formation"
      :monthlyCost="$monthlyCost" :monthlyEnrollmentsCount="$monthlyEnrollmentsCount" />

    {{-- Filtres --}}
    <x-organisateur.parts.filters :search="$search" :statusFilter="$statusFilter"
      routeName="organisateur.formations.students" :routeParams="[$team, $formation]" />

    {{-- Liste des élèves --}}
    @if($studentSummaries->count() > 0)
    <div class="space-y-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Liste des eleves ({{ $studentSummaries->count()
          }})</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Progression, temps passe et acces rapide au rapport
          detaille.</p>
      </div>

      <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($studentSummaries as $summary)
        <x-organisateur.parts.student-card :summary="$summary" :formation="$formation" :team="$team" />
        @endforeach
      </div>
    </div>
    @else
    <x-organisateur.parts.empty-state icon="users" title="Aucun eleve"
      description="Aucun eleve n'est encore inscrit a cette formation." />
    @endif
  </div>
</x-organisateur-layout>