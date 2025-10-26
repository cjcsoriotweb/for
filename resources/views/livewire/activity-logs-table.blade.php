<div>
  {{-- Search and Filter Form --}}
  <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Recherche
        </label>
        <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="IP, navigateur, page..."
          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
      </div>

      <div>
        <label for="lesson_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Leçon
        </label>
        <select wire:model.live="lessonFilter"
          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
          <option value="">Toutes les leçons</option>
          @foreach($availableLessons as $lesson)
          <option value="{{ $lesson['id'] }}">{{ $lesson['title'] }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Date début
        </label>
        <input type="date" id="start_date" wire:model.live="startDate"
          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
      </div>

      <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Date fin
        </label>
        <input type="date" id="end_date" wire:model.live="endDate"
          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
      </div>

      <div class="flex items-end">
        <button type="button" wire:click="clearFilters"
          class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
            </path>
          </svg>
          Réinitialiser
        </button>
      </div>
    </div>
  </div>

  {{-- Activity Summary Cards --}}
  @if($activitySummary && $activitySummary['total_sessions'] > 0)
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
      <div class="text-sm font-medium text-blue-600 dark:text-blue-300">Sessions totales</div>
      <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $activitySummary['total_sessions'] }}</div>
    </div>
    <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
      <div class="text-sm font-medium text-green-600 dark:text-green-300">Pages vues</div>
      <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $activitySummary['total_page_views'] }}
      </div>
    </div>
    <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-4">
      <div class="text-sm font-medium text-purple-600 dark:text-purple-300">IPs uniques</div>
      <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $activitySummary['unique_ips'] }}</div>
    </div>
    <div class="bg-orange-50 dark:bg-orange-900 rounded-lg p-4">
      <div class="text-sm font-medium text-orange-600 dark:text-orange-300">Temps moyen/session</div>
      <div class="text-2xl font-bold text-orange-900 dark:text-orange-100">
        @if($activitySummary['average_session_duration'] > 0)
        {{ floor($activitySummary['average_session_duration'] / 60) }}min {{
        $activitySummary['average_session_duration'] % 60 }}s
        @else
        N/A
        @endif
      </div>
    </div>
  </div>
  @endif

  {{-- Activity Table --}}
  @if($activityLogs && $activityLogs->count() > 0)
  <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          Activités détaillées ({{ method_exists($activityLogs, 'total') ? $activityLogs->total() : $activityLogs->count() }} résultats)
        </h3>
        <div class="flex items-center space-x-2">
          <label for="per_page" class="text-sm text-gray-500 dark:text-gray-400">Afficher:</label>
          <select wire:model.live="perPage"
            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Date/Heure
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Formation/Leçon
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              IP Address
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Navigateur
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Appareil
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Durée
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Action
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          @foreach($activityLogs as $activity)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
              <div class="flex flex-col">
                <span>{{ $activity->created_at->format('d/m/Y') }}</span>
                <span class="text-xs text-gray-500">{{ $activity->created_at->format('H:i:s') }}</span>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
              <div class="flex flex-col">
                <span class="font-medium">{{ $activity->getFormationName() ?? 'N/A' }}</span>
                <span class="text-xs text-gray-500">{{ $activity->getLessonName() ?? 'Page générale' }}</span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ $activity->formatted_ip }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ $activity->browser_info ?? 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ $activity->device_type }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
              {{ $activity->formatted_duration }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $activity->method === 'GET' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                   ($activity->method === 'POST' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                   'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200') }}">
                {{ $activity->method }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
      {{ $activityLogs->links() }}
    </div>
  </div>
  @else
  <div class="text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
      </path>
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucune activité trouvée</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
      @if($search || $lessonFilter || $startDate || $endDate)
      Aucun résultat ne correspond à vos critères de recherche.
      @else
      Aucune activité n'a été enregistrée pour cet étudiant.
      @endif
    </p>
    @if($search || $lessonFilter || $startDate || $endDate)
    <div class="mt-4">
      <button type="button" wire:click="clearFilters"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
        Effacer les filtres
      </button>
    </div>
    @endif
  </div>
  @endif

  {{-- Loading indicator --}}
  <div wire:loading class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-4">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <span class="text-gray-900 dark:text-white">Chargement...</span>
    </div>
  </div>
</div>
