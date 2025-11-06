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
            Découvrez toutes les formations disponibles
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
   

      {{-- Formations Activées --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">

        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Activées</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $visibleFormations->count() }} / <b>{{ $allFormations->count() }} </b></p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pour {{ $team->name }}</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
              </path>
            </svg>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- Search and Filters Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
      <form method="GET" action="{{ route('organisateur.catalogue', $team) }}" class="space-y-6">
        {{-- Search Bar --}}
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <input type="text" name="search" value="{{ $search }}"
            placeholder="Rechercher par titre, description ou niveau..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        </div>

        {{-- Filters and Sort --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          {{-- Filter by visibility --}}
          <div>
            <label for="filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Filtrer par
            </label>
            <select name="filter" id="filter"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="all" {{ $filter==='all' ? 'selected' : '' }}>Toutes les formations</option>
              <option value="visible" {{ $filter==='visible' ? 'selected' : '' }}>Activées ({{
                $visibleFormations->count() }})</option>
              <option value="hidden" {{ $filter==='hidden' ? 'selected' : '' }}>Non activées ({{ $allFormations->count()
                - $visibleFormations->count() }})</option>
            </select>
          </div>

          {{-- Sort by --}}
          <div>
            <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Trier par
            </label>
            <select name="sort" id="sort"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="title" {{ $sortBy==='title' ? 'selected' : '' }}>Titre</option>
              <option value="total_duration_minutes" {{ $sortBy==='total_duration_minutes' ? 'selected' : '' }}>Durée
              </option>
              <option value="created_at" {{ $sortBy==='created_at' ? 'selected' : '' }}>Date de création</option>
              <option value="learners_count" {{ $sortBy==='learners_count' ? 'selected' : '' }}>Nombre d'apprenants
              </option>
              <option value="lessons_count" {{ $sortBy==='lessons_count' ? 'selected' : '' }}>Nombre de leçons</option>
            </select>
          </div>

          {{-- Sort direction --}}
          <div>
            <label for="direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Ordre
            </label>
            <select name="direction" id="direction"
              class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="asc" {{ $sortDirection==='asc' ? 'selected' : '' }}>Croissant</option>
              <option value="desc" {{ $sortDirection==='desc' ? 'selected' : '' }}>Décroissant</option>
            </select>
          </div>
        </div>

        {{-- Search and Reset buttons --}}
        <div class="flex items-center gap-3">
          <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-purple-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Rechercher
          </button>

          @if($search || $filter !== 'all' || $sortBy !== 'title' || $sortDirection !== 'asc')
          <a href="{{ route('organisateur.catalogue', $team) }}"
            class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
              </path>
            </svg>
            Réinitialiser
          </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  {{-- Results Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Formations disponibles</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
          {{ $allFormations->count() }} formation{{ $allFormations->count() > 1 ? 's' : '' }} trouvée{{
          $allFormations->count() > 1 ? 's' : '' }}
          @if($search)
          pour "{{ $search }}"
          @endif
        </p>
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

    @if($allFormations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($allFormations as $formation)
      <x-organisateur.parts.formation-catalogue-card :formation="$formation" :team="$team"
        :isVisible="$visibleFormations->contains('id', $formation->id)" />
      @endforeach
    </div>
    @else
    <div class="text-center py-16">
      <div
        class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
        @if($search)
        Aucune formation trouvée
        @else
        Aucune formation disponible
        @endif
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        @if($search)
        Essayez de modifier vos critères de recherche ou réinitialisez les filtres.
        @else
        Il n'y a actuellement aucune formation dans le catalogue.
        @endif
      </p>
      @if($search || $filter !== 'all' || $sortBy !== 'title' || $sortDirection !== 'asc')
      <a href="{{ route('organisateur.catalogue', $team) }}"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
          </path>
        </svg>
        Réinitialiser les filtres
      </a>
      @endif
    </div>
    @endif
  </div>

</x-organisateur-layout>