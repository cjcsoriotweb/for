<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
  <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Progression détaillée par leçon</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
      Suivi granularisé de chaque chapitre et leçon suivis par l’étudiant.
    </p>
  </div>
  <div class="px-4 py-5 sm:px-6">
    @if($lessons->count() > 0)
    @php
    $groupedLessons = $lessons->groupBy(function ($lesson) {
        return optional($lesson->chapter)->title ?? 'Leçons sans chapitre';
    });
    @endphp
    <div class="space-y-4">
      @foreach($groupedLessons as $chapterTitle => $chapterLessons)
      <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
          <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $chapterTitle }}</h4>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
          @foreach($chapterLessons as $lesson)
          <div class="px-4 py-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  @if($lesson->pivot->status === 'completed')
                  <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"></path>
                  </svg>
                  @elseif($lesson->pivot->status === 'in_progress')
                  <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  @else
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  @endif
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    @switch($lesson->lessonable_type)
                      @case('App\\Models\\VideoContent')
                        Vidéo
                        @break
                      @case('App\\Models\\TextContent')
                        Texte
                        @break
                      @case('App\\Models\\Quiz')
                        Quiz
                        @break
                      @default
                        Contenu
                    @endswitch
                  </p>
                </div>
              </div>

              <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                @if($lesson->pivot->started_at instanceof \Carbon\Carbon)
                <div>Démarrée : {{ $lesson->pivot->started_at->format('d/m/Y H:i') }}</div>
                @endif

                @if($lesson->pivot->completed_at instanceof \Carbon\Carbon)
                <div>Terminée : {{ $lesson->pivot->completed_at->format('d/m/Y H:i') }}</div>
                @endif

                @if($lesson->pivot->watched_seconds)
                <div>{{ floor($lesson->pivot->watched_seconds / 60) }}min {{ $lesson->pivot->watched_seconds % 60 }}s</div>
                @endif

                @if($lesson->pivot->read_percent)
                <div>{{ $lesson->pivot->read_percent }}% lu</div>
                @endif

                @if($lesson->pivot->attempts)
                <div>{{ $lesson->pivot->attempts }} tentative(s)</div>
                @endif

                @if($lesson->pivot->best_score && $lesson->pivot->max_score)
                <div>{{ round(($lesson->pivot->best_score / $lesson->pivot->max_score) * 100, 1) }}%</div>
                @endif
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
    @else
    <p class="text-gray-500 dark:text-gray-400">Aucune leçon disponible.</p>
    @endif
  </div>
</div>
