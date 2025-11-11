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


  {{-- Results Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Formations disponibles</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
          {{ $allFormations->count() }} formation{{ $allFormations->count() > 1 ? 's' : '' }} disponible{{
          $allFormations->count() > 1 ? 's' : '' }}
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
        Aucune formation disponible
      </h3>
      <p class="text-gray-600 dark:text-gray-400 mb-6">
        Il n'y a actuellement aucune formation dans le catalogue.
      </p>
    </div>
    @endif
  </div>

</x-organisateur-layout>
