<div class="space-y-8">
  {{-- Overview Stats Cards --}}
  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-lg bg-white shadow dark:bg-gray-800">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Lecons terminees</dt>
            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $completedLessons }}/{{ $totalLessons }}
            </dd>
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-lg bg-white shadow dark:bg-gray-800">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Temps passe</dt>
            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalHours }}h {{ $totalMinutes }}min
            </dd>
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-lg bg-white shadow dark:bg-gray-800">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Score moyen aux quiz</dt>
            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $averageQuizScore }}%</dd>
          </div>
        </div>
      </div>
    </div>

    <div class="rounded-lg bg-white shadow dark:bg-gray-800">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l6-6m0 0v6m0-6h-6"></path>
            </svg>
          </div>
          <div class="ml-5 flex-1">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tentatives de quiz</dt>
            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $quizAttempts->count() }}</dd>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- General Information --}}
  <div class="rounded-lg bg-white shadow dark:bg-gray-800">
    <div class="border-b border-gray-200 px-4 py-5 dark:border-gray-700 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Informations generales</h3>
    </div>
    <div class="px-4 py-5 sm:px-6">
      <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            @if($studentData->pivot->status === 'completed')
            <span
              class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">Terminee</span>
            @elseif($studentData->pivot->status === 'in_progress')
            <span
              class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">En
              cours</span>
            @else
            <span
              class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-200">Inscrit</span>
            @endif
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Score global</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            @if($studentData->pivot->score_total && $studentData->pivot->max_score_total)
            {{ round(($studentData->pivot->score_total / $studentData->pivot->max_score_total) * 100, 1) }}%
            @else
            Non renseigne
            @endif
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'inscription</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{
            optional($studentData->pivot->enrolled_at)->format('d/m/Y @ H:i:s') ?: 'Non renseigne' }}</dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Derniere activite</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{
            optional($studentData->pivot->last_seen_at)->format('d/m/Y @ H:i:s') ?: 'Non renseigne' }}</dd>
        </div>

        @if($studentData->pivot->completed_at)
        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de completion</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{
            optional($studentData->pivot->completed_at)->format('d/m/Y @ H:i:s') ?: 'Non renseigne' }}</dd>
        </div>
        @endif

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Progression</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $completedLessons }} lecons terminees sur {{ $totalLessons }} ({{ $totalLessons > 0 ?
            round(($completedLessons / $totalLessons) * 100, 1) : 0 }}%)
          </dd>
        </div>
      </dl>
    </div>
  </div>
</div>