<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
  <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Activité & connexions</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Analyse des connexions, appareils et pages consultées
          par l'élève.</p>
      </div>
      @if(isset($activitySummary))
      <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
        <span class="inline-flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
          {{ $activitySummary['total_sessions'] ?? 0 }} sessions
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
          {{ $activitySummary['total_page_views'] ?? 0 }} pages vues
        </span>
        <span class="inline-flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
          {{ $activitySummary['unique_ips'] ?? 0 }} IPs uniques
        </span>
      </div>
      @endif
    </div>
  </div>
  <div class="px-4 py-5 sm:px-6">
    @livewire('activity-logs-table', [
        'userId' => $student->id,
        'lessons' => $lessons,
        'formationId' => $formation->id,
    ])
  </div>
</div>
