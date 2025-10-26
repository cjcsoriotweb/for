<div class="space-y-8">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Leçons complétées</dt>
              <dd class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $completedLessons }}/{{ $totalLessons }}
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Temps passé</dt>
              <dd class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $totalHours }}h {{ $totalMinutes }}min
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Score moyen aux quiz</dt>
              <dd class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $averageQuizScore }}%
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
      <div class="p-5">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l6-6m0 0v6m0-6h-6"></path>
            </svg>
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tentatives de quiz</dt>
              <dd class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $quizAttempts->count() }}
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Informations générales</h3>
    </div>
    <div class="px-4 py-5 sm:px-6">
      <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            @if($studentData->pivot->status === 'completed')
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
              Terminé
            </span>
            @elseif($studentData->pivot->status === 'in_progress')
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
              En cours
            </span>
            @else
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
              Inscrit
            </span>
            @endif
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Score global</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            @if($studentData->pivot->score_total && $studentData->pivot->max_score_total)
            {{ round(($studentData->pivot->score_total / $studentData->pivot->max_score_total) * 100, 1) }}%
            @else
            N/A
            @endif
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'inscription</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $studentData->pivot->enrolled_at instanceof \Carbon\Carbon
              ? $studentData->pivot->enrolled_at->format('d/m/Y à H:i:s')
              : 'N/A' }}
          </dd>
        </div>

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dernière activité</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $studentData->pivot->last_seen_at instanceof \Carbon\Carbon
              ? $studentData->pivot->last_seen_at->format('d/m/Y à H:i:s')
              : 'N/A' }}
          </dd>
        </div>

        @if($studentData->pivot->completed_at instanceof \Carbon\Carbon)
        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de complétion</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $studentData->pivot->completed_at->format('d/m/Y à H:i:s') }}
          </dd>
        </div>
        @endif

        <div>
          <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Progression</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $completedLessons }} leçons terminées sur {{ $totalLessons }}
            ({{ $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 1) : 0 }}%)
          </dd>
        </div>
      </dl>
    </div>
  </div>
</div>
