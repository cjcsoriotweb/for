{{-- Filters Component --}}
@props(['search' => '', 'statusFilter' => '', 'selectedMonth' => '', 'availableMonths' => [], 'routeName' => '',
'routeParams' => []])

<div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
  <div class="mb-4 flex items-center gap-2">
    <div
      class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 text-white">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
        </path>
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtres</h3>
  </div>

  <form method="GET" class="grid gap-6 md:grid-cols-3">
    {{-- Search Filter --}}
    <div class="space-y-2">
      <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recherche</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <input id="search" name="search" value="{{ $search }}"
          class="block w-full pl-10 rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
          placeholder="Nom ou email">
      </div>
    </div>

    {{-- Status Filter --}}
    <div class="space-y-2">
      <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Statut</label>
      <div class="relative">
        <select id="status" name="status"
          class="block w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
          <option value="">Tous les statuts</option>
          <option value="completed" @selected($statusFilter==='completed' )>Terminée</option>
          <option value="in_progress" @selected($statusFilter==='in_progress' )>En cours</option>
          <option value="enrolled" @selected($statusFilter==='enrolled' )>Inscrit</option>
        </select>
      </div>
    </div>

    {{-- Month Filter (for cost page) --}}
    @if(!empty($availableMonths))
    <div class="space-y-2">
      <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mois</label>
      <div class="relative">
        <select id="month" name="month"
          class="block w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
          @foreach($availableMonths as $month)
          <option value="{{ $month }}" @selected($selectedMonth===$month)>
            {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
          </option>
          @endforeach
        </select>
      </div>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex items-end gap-3">
      <button type="submit"
        class="group inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 text-sm font-medium text-white shadow-lg transition-all hover:from-blue-700 hover:to-purple-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 md:w-auto">
        <svg class="h-4 w-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Appliquer
      </button>

      @if($search || $statusFilter || $selectedMonth)
      <a href="{{ route($routeName, $routeParams) }}"
        class="group inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 md:w-auto">
        <svg class="h-4 w-4 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
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