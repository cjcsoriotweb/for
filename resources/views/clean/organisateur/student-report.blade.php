<x-organisateur-layout :team="$team">
  {{-- Messages de notification --}}
  @if(session('success'))
  <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    {{ session("success") }}
  </div>
  @endif @if(session('warning'))
  <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
    {{ session("warning") }}
  </div>
  @endif @if(session('error'))
  <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    {{ session("error") }}
  </div>
  @endif

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
          <li>
            <div>
              <a href="{{ route('organisateur.index', $team) }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                Formations
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
                class="ml-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                {{ $formation->title }}
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <span class="ml-4 text-gray-500 dark:text-gray-400">Rapport - {{ $student->name }}</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Rapport détaillé</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $student->name }} - {{ $student->email }}</p>
        </div>

        <div class="flex items-center space-x-3">
          <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
              </path>
            </svg>
            Retour aux élèves
          </a>
        </div>
      </div>
    </div>

    {{-- Statistiques générales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Leçons</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $completedLessons }}/{{ $totalLessons
                  }}</dd>
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
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalHours }}h {{ $totalMinutes }}min
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Score moyen quiz</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $averageQuizScore }}%</dd>
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tentatives quiz</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $quizAttempts->count() }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Informations générales --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-8">
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
                Terminée
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
              {{ $studentData->pivot->enrolled_at && $studentData->pivot->enrolled_at instanceof \Carbon\Carbon ?
              $studentData->pivot->enrolled_at->format('d/m/Y à H:i:s') : 'N/A' }}
            </dd>
          </div>

          <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dernière activité</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
              {{ $studentData->pivot->last_seen_at && $studentData->pivot->last_seen_at instanceof \Carbon\Carbon ?
              $studentData->pivot->last_seen_at->format('d/m/Y à H:i:s') : 'N/A' }}
            </dd>
          </div>

          @if($studentData->pivot->completed_at && $studentData->pivot->completed_at instanceof \Carbon\Carbon)
          <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de completion</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
              {{ $studentData->pivot->completed_at->format('d/m/Y à H:i:s') }}
            </dd>
          </div>
          @endif

          <div>
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Progression</dt>
            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
              {{ $completedLessons }} leçons terminées sur {{ $totalLessons }} ({{ $totalLessons > 0 ?
              round(($completedLessons / $totalLessons) * 100, 1) : 0 }}%)
            </dd>
          </div>
        </dl>
      </div>
    </div>

    {{-- Progression par leçon --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-8">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Progression détaillée par leçon</h3>
      </div>
      <div class="px-4 py-5 sm:px-6">
        @if($lessons->count() > 0)
        <div class="space-y-4">
          @foreach($lessons->groupBy('chapters.title') as $chapterTitle => $chapterLessons)
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $chapterTitle }}</h4>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
              @foreach($chapterLessons as $lesson)
              <div class="px-4 py-4">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
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
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $lesson->lessonable_type ===
                          'App\Models\VideoContent' ? 'Vidéo' : ($lesson->lessonable_type === 'App\Models\TextContent' ?
                          'Texte' : 'Quiz') }}</p>
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    @if($lesson->pivot->started_at && $lesson->pivot->started_at instanceof \Carbon\Carbon)
                    <div>Démarrée: {{ $lesson->pivot->started_at->format('d/m/Y H:i') }}</div>
                    @endif

                    @if($lesson->pivot->completed_at && $lesson->pivot->completed_at instanceof \Carbon\Carbon)
                    <div>Terminée: {{ $lesson->pivot->completed_at->format('d/m/Y H:i') }}</div>
                    @endif

                    @if($lesson->pivot->watched_seconds)
                    <div>{{ floor($lesson->pivot->watched_seconds / 60) }}min {{ $lesson->pivot->watched_seconds % 60
                      }}s</div>
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

    {{-- Historique des quiz --}}
    @if($quizAttempts->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-8">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Historique des quiz</h3>
      </div>
      <div class="px-4 py-5 sm:px-6">
        <div class="space-y-4">
          @foreach($quizAttempts as $attempt)
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $attempt->lesson->title }}</h4>
              <span class="text-sm text-gray-500 dark:text-gray-400">{{ $attempt->created_at->format('d/m/Y H:i')
                }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
              <div>
                <span class="font-medium text-gray-500 dark:text-gray-400">Score:</span>
                <span class="ml-2 text-gray-900 dark:text-white">{{ $attempt->score ? round($attempt->score, 1) : 0
                  }}%</span>
              </div>

              <div>
                <span class="font-medium text-gray-500 dark:text-gray-400">Temps passé:</span>
                <span class="ml-2 text-gray-900 dark:text-white">{{ $attempt->duration_seconds ?
                  floor($attempt->duration_seconds /
                  60) . 'min ' . ($attempt->duration_seconds % 60) . 's' : 'N/A' }}</span>
              </div>

              <div>
                <span class="font-medium text-gray-500 dark:text-gray-400">Réponses:</span>
                <span class="ml-2 text-gray-900 dark:text-white">{{ $attempt->answers->count() }}/{{
                  $attempt->lesson->lessonable && $attempt->lesson->lessonable->quizQuestions ?
                  $attempt->lesson->lessonable->quizQuestions->count() : 0 }}</span>
              </div>
            </div>

            @if($attempt->answers->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
              <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Réponses détaillées:</p>
              <div class="space-y-3">
                @foreach($attempt->answers as $answer)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                  <div class="flex items-start">
                    <span
                      class="w-6 h-6 rounded-full {{ $answer->is_correct ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                      {{ $answer->is_correct ? '✓' : '✗' }}
                    </span>
                    <div class="flex-1">
                      <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                        {{ $answer->question ? $answer->question->question : 'Question non trouvée' }}
                      </p>

                      @if($answer->choice)
                      <div class="text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Réponse choisie:</span>
                        <span
                          class="ml-1 {{ $answer->is_correct ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                          {{ $answer->choice->choice_text }}
                        </span>
                      </div>
                      @endif

                      @if($answer->question && $answer->question->quizChoices)
                      <div class="text-xs mt-1">
                        <span class="text-gray-500 dark:text-gray-400">Réponses correctes:</span>
                        <div class="ml-1 flex flex-wrap gap-1">
                          @foreach($answer->question->quizChoices->where('is_correct', true) as $correctChoice)
                          <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            {{ $correctChoice->choice_text }}
                          </span>
                          @endforeach
                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>
</x-organisateur-layout>