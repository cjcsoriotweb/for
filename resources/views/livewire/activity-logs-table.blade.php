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
          Activités détaillées ({{ method_exists($activityLogs, 'total') ? $activityLogs->total() :
          $activityLogs->count() }} résultats)
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
              <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2 text-sm font-medium">
                  @php
                  $iconVariant = match (true) {
                      $activity->is_quiz => 'quiz',
                      $activity->lesson_type === 'text' => 'text',
                      $activity->lesson_type === 'video' => 'video',
                      $activity->is_lesson => 'lesson',
                      default => 'page',
                  };
                  $iconClasses = match ($iconVariant) {
                      'quiz' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
                      'text' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-200',
                      'video', 'lesson' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                      default => 'bg-slate-100 text-slate-500 dark:bg-slate-700/40 dark:text-slate-300',
                  };
                  @endphp
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl {{ $iconClasses }}">
                    @if($iconVariant === 'quiz')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 3c4.97 0 9 4.03 9 9s-4.03 9-9 9-9-4.03-9-9 4.03-9 9-9Zm0 2c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7Zm-.25 3.75a1.25 1.25 0 0 1 2.5 0c0 .69-.56 1.25-1.25 1.25h-.25v1h.25c1.247 0 2.25-1.003 2.25-2.25s-1.003-2.25-2.25-2.25-2.25 1.003-2.25 2.25H8.75c0-2.07 1.68-3.75 3.75-3.75Zm0 7.5c.414 0 .75.336.75.75v.75h-1.5v-.75c0-.414.336-.75.75-.75Z" />
                    </svg>
                    @elseif($iconVariant === 'text')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7 4.25a.75.75 0 0 0 0 1.5H16.5a.75.75 0 0 0 0-1.5H7Zm0 4a.75.75 0 0 0 0 1.5H13a.75.75 0 0 0 0-1.5H7Zm0 4a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5H7Zm0 4a.75.75 0 0 0 0 1.5h6a.75.75 0 0 0 0-1.5H7Z" />
                    </svg>
                    @elseif($iconVariant === 'video' || $iconVariant === 'lesson')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17 5.25a1 1 0 0 1 1 1v11.5a1 1 0 0 1-1.555.832L10 15.861V8.139l6.444-3.721A1 1 0 0 1 17 5.25Z" />
                      <path d="M5 6.25C5 5.007 6.007 4 7.25 4h2.5c1.243 0 2.25 1.007 2.25 2.25v11.5C12 18.993 10.993 20 9.75 20h-2.5C5.007 20 4 18.993 4 17.75V6.25Z" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M3 5.25C3 4.007 4.007 3 5.25 3h13.5C19.993 3 21 4.007 21 5.25v13.5c0 1.243-1.007 2.25-2.25 2.25H5.25C4.007 21 3 19.993 3 18.75V5.25Z" />
                      <path d="M7.5 7.5h9v9h-9z" fill="#fff" />
                    </svg>
                    @endif
                  </span>
                  <span>{{ $activity->formation_label }}</span>
                </div>
                  <span class="text-xs text-gray-500">
                    {{ $activity->getLessonName() ?? $activity->lesson_type_label }}
                  </span>
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
