<x-organisateur-layout :team="$team">

  {{-- Welcome Header --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
            Bienvenue, {{ $team->name }}
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-400">
            Gérez vos formations et suivez vos étudiants
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

  {{-- Quick Stats --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      {{-- Balance Card --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
        <div
          class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 opacity-0 transition-opacity group-hover:opacity-100">
        </div>
        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Solde disponible</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($team->money, 0, ',', ' ') }} €
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dernière mise à jour: {{ now()->format('d/m/Y') }}
            </p>
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
        <div class="mt-4">
          <button
            class="w-full rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 px-4 py-2 text-sm font-medium text-white transition-all hover:from-emerald-600 hover:to-teal-700">
            Recharger le solde
          </button>
        </div>
      </div>

      {{-- Users Card --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
        <div
          class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 transition-opacity group-hover:opacity-100">
        </div>
        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Utilisateurs actifs</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $team->users->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Membres de l'équipe</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
              </path>
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <button
            class="w-full rounded-lg border border-blue-300 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition-all hover:bg-blue-50 dark:border-blue-600 dark:bg-gray-800 dark:text-blue-300">
            Gérer les utilisateurs
          </button>
        </div>
      </div>

      {{-- Formations Card --}}
      <div
        class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-50 to-pink-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
        <div
          class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 transition-opacity group-hover:opacity-100">
        </div>
        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Formations actives</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $formations->count() }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Formations disponibles</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
        </div>
        <div class="mt-4">
          <button
            class="w-full rounded-lg bg-gradient-to-r from-purple-500 to-pink-600 px-4 py-2 text-sm font-medium text-white transition-all hover:from-purple-600 hover:to-pink-700">
            Voir les formations
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Formations Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Formations disponibles</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Découvrez et gérez vos formations</p>
      </div>
      <div class="hidden md:block">
        <button
          class="rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-3 text-sm font-medium text-white transition-all hover:from-blue-600 hover:to-purple-700">
          <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Nouvelle formation
        </button>
      </div>
    </div>

    @if($formations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($formations as $formation)
      <x-organisateur.parts.formation-card :formation="$formation" :team="$team" />
      @endforeach
    </div>
    @else
    <x-organisateur.parts.empty-state icon="formation" title="Aucune formation disponible"
      description="Commencez par créer votre première formation pour permettre à vos étudiants d'apprendre." />
    @endif
  </div>

</x-organisateur-layout>