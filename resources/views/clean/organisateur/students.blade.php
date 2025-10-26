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
              <span class="ml-4 text-gray-500 dark:text-gray-400">{{ $formation->title }}</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Élèves inscrits</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $formation->title }} - {{ $formation->description }}</p>
        </div>

        <div class="flex items-center space-x-3">
          <a href="{{ route('organisateur.index', $team) }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
              </path>
            </svg>
            Retour aux formations
          </a>
        </div>
      </div>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                </path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total élèves</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->count() }}</dd>
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Terminées</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->where('pivot.status',
                  'completed')->count() }}</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En cours</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->where('pivot.status',
                  'in_progress')->count() }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                </path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Non commencées</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->where('pivot.status',
                  'enrolled')->count() }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Liste des élèves --}}
    @if($students->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
          Liste des élèves ({{ $students->count() }})
        </h3>
      </div>

      <ul class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($students as $student)
        <li class="px-4 py-4 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ substr($student->name, 0, 2) }}
                  </span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ $student->name }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                  {{ $student->email }}
                </div>
              </div>
            </div>

            <div class="flex items-center space-x-4">
              {{-- Statut --}}
              <div class="flex items-center">
                @if($student->pivot->status === 'completed')
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"></path>
                  </svg>
                  Terminée
                </span>
                @elseif($student->pivot->status === 'in_progress')
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  En cours
                </span>
                @else
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"></path>
                  </svg>
                  Inscrit
                </span>
                @endif
              </div>

              {{-- Score --}}
              @if($student->pivot->score_total && $student->pivot->max_score_total)
              <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ round(($student->pivot->score_total / $student->pivot->max_score_total) * 100, 1) }}%
              </div>
              @endif

              {{-- Dates et activité --}}
              <div class="text-sm text-gray-500 dark:text-gray-400">
                <div class="space-y-1">
                  <div><strong>Inscrit:</strong> {{ $student->pivot->enrolled_at && $student->pivot->enrolled_at
                    instanceof \Carbon\Carbon ? $student->pivot->enrolled_at->format('d/m/Y à H:i') : 'N/A' }}</div>

                  @if($student->pivot->last_seen_at && $student->pivot->last_seen_at instanceof \Carbon\Carbon)
                  <div><strong>Dernière connexion:</strong> {{ $student->pivot->last_seen_at->format('d/m/Y à H:i') }}
                  </div>
                  @endif

                  @if($student->pivot->completed_at && $student->pivot->completed_at instanceof \Carbon\Carbon)
                  <div><strong>Terminée:</strong> {{ $student->pivot->completed_at->format('d/m/Y à H:i') }}</div>
                  @endif

                  {{-- Temps total passé --}}
                  @php
                  $totalTime = 0;
                  $lessonCount = 0;
                  foreach($student->lessons as $lesson) {
                  if($lesson->pivot->watched_seconds) {
                  $totalTime += $lesson->pivot->watched_seconds;
                  $lessonCount++;
                  }
                  }
                  $totalHours = floor($totalTime / 3600);
                  $totalMinutes = floor(($totalTime % 3600) / 60);
                  @endphp

                  @if($totalTime > 0)
                  <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                    <div><strong>Temps passé:</strong> {{ $totalHours }}h {{ $totalMinutes }}min</div>
                    <div class="text-xs text-gray-400">{{ $lessonCount }} leçon(s) consultée(s)</div>
                  </div>
                  @endif

                  {{-- Bouton rapport détaillé --}}
                  <div class="mt-3 pt-2 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('organisateur.formations.students.report', [$team, $formation, $student]) }}"
                      class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                      </svg>
                      Rapport détaillé
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
    @else
    <div class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
        </path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun élève</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aucun élève n'est encore inscrit à cette formation.</p>
    </div>
    @endif
  </div>
</x-organisateur-layout>