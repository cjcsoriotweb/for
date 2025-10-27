<x-organisateur-layout :team="$team">

  {{-- Header --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Catalogue des formations
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-400">
            Découvrez toutes les formations disponibles et leurs tarifs
          </p>
        </div>
        <div class="hidden md:block">
          <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full blur opacity-30">
            </div>
            <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 p-4 rounded-full">
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Stats Cards --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Total Formations --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">

        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total formations</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $formations->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formations disponibles</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Prix moyen --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">

        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Prix moyen</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ $formations->avg('money_amount') ? number_format($formations->avg('money_amount'), 0, ',', ' ') : '0'
              }} €
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Par formation</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
              </path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Prix total --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-pink-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">

        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Valeur totale</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ number_format($formations->sum('money_amount'), 0, ',', ' ') }} €
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Catalogue complet</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
              </path>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Catalogue Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Formations disponibles</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Parcourez notre catalogue complet de formations</p>
      </div>
      <div class="hidden md:block">
        <a href="{{ route('organisateur.index', $team) }}"
          class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
          <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
          </svg>
          Retour à l'accueil
        </a>
      </div>
    </div>

    @if($formations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($formations as $formation)
      <x-organisateur.parts.formation-catalogue-card :formation="$formation" :team="$team" />
      @endforeach
    </div>
    @else
    <x-organisateur.parts.empty-state icon="formation" title="Aucune formation disponible"
      description="Il n'y a actuellement aucune formation dans le catalogue." />
    @endif
  </div>

</x-organisateur-layout>