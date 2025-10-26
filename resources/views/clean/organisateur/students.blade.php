<x-organisateur-layout :team="$team">
  {{-- Messages de notification --}}
  @if(session('success'))
  <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    {{ session('success') }}
  </div>
  @endif
  @if(session('warning'))
  <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
    {{ session('warning') }}
  </div>
  @endif
  @if(session('error'))
  <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    {{ session('error') }}
  </div>
  @endif

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <nav class="flex mb-4" aria-label="Fil d'Ariane">
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

      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Eleves inscrits</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $formation->title }} - {{ $formation->description }}</p>
        </div>

        <div class="flex items-center gap-3">
          <a href="{{ route('organisateur.index', $team) }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total eleves</dt>
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
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Terminees</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->where('pivot.status', 'completed')->count() }}</dd>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">En cours</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $students->where('pivot.status', 'in_progress')->count() }}</dd>
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
                  d="M12 6v6h4"></path>
              </svg>
            </div>
            <div class="ml-5 w-0 flex-1">
              <dl>
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Temps cumule</dt>
                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                  @php
                  $totalSeconds = 0;
                  foreach ($students as $student) {
                      foreach ($student->lessons as $lesson) {
                          $totalSeconds += $lesson->pivot->watched_seconds ?? 0;
                      }
                  }
                  $hours = floor($totalSeconds / 3600);
                  $minutes = floor(($totalSeconds % 3600) / 60);
                  @endphp
                  {{ $hours }}h {{ $minutes }}min
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Liste des eleves --}}
    @if($students->count() > 0)
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Liste des eleves ({{ $students->count() }})</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400">Statut, temps passe et acces rapide au rapport detaille.</p>
        </div>
      </div>

      <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($students as $student)
        @php
        $totalTime = 0;
        $lessonCount = 0;
        foreach ($student->lessons as $lesson) {
            if ($lesson->pivot->watched_seconds) {
                $totalTime += $lesson->pivot->watched_seconds;
                $lessonCount++;
            }
        }
        $totalHours = floor($totalTime / 3600);
        $totalMinutes = floor(($totalTime % 3600) / 60);
        @endphp

        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex flex-col justify-between">
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-200 text-lg font-semibold uppercase">
                {{ \Illuminate\Support\Str::of($student->name)->substr(0, 2)->upper() }}
              </span>
              <div>
                <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $student->name }}</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</p>
              </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
              @php
              $statusMap = [
                  'completed' => ['label' => 'Terminee', 'classes' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
                  'in_progress' => ['label' => 'En cours', 'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'],
                  'enrolled' => ['label' => 'Inscrit', 'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'],
              ];
              $status = $statusMap[$student->pivot->status] ?? $statusMap['enrolled'];
              @endphp
              <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium {{ $status['classes'] }}">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"></path>
                </svg>
                {{ $status['label'] }}
              </span>

              @if($student->pivot->score_total && $student->pivot->max_score_total)
              <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-3 py-1 text-xs font-medium">
                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 00-2 2v9.586A2 2 0 003.414 17L7 13.414a2 2 0 012.828 0L13.414 17A2 2 0 0015 17V5a2 2 0 00-2-2H4z" />
                </svg>
                {{ round(($student->pivot->score_total / $student->pivot->max_score_total) * 100, 1) }}%
              </span>
              @endif
            </div>

            <dl class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
              <div class="flex items-center justify-between">
                <dt class="font-medium text-gray-500 dark:text-gray-400">Inscrit</dt>
                <dd>
                  {{ $student->pivot->enrolled_at instanceof \Carbon\Carbon ? $student->pivot->enrolled_at->format('d/m/Y @ H:i') : 'N/A' }}
                </dd>
              </div>
              @if($student->pivot->last_seen_at instanceof \Carbon\Carbon)
              <div class="flex items-center justify-between">
                <dt class="font-medium text-gray-500 dark:text-gray-400">Derniere connexion</dt>
                <dd>{{ $student->pivot->last_seen_at->format('d/m/Y @ H:i') }}</dd>
              </div>
              @endif
              @if($student->pivot->completed_at instanceof \Carbon\Carbon)
              <div class="flex items-center justify-between">
                <dt class="font-medium text-gray-500 dark:text-gray-400">Terminee</dt>
                <dd>{{ $student->pivot->completed_at->format('d/m/Y @ H:i') }}</dd>
              </div>
              @endif
            </dl>
          </div>

          <div class="mt-4 border-t border-dashed border-gray-200 dark:border-gray-700 pt-4">
            @if($totalTime > 0)
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
              <div>
                <p class="font-medium text-gray-500 dark:text-gray-400">Temps passe</p>
                <p>{{ $totalHours }}h {{ $totalMinutes }}min</p>
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-500 dark:text-gray-400">Lecons consultees</p>
                <p>{{ $lessonCount }}</p>
              </div>
            </div>
            @else
            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune activite enregistree pour le moment.</p>
            @endif

            <a href="{{ route('organisateur.formations.students.report', [$team, $formation, $student]) }}"
              class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Rapport detaille
            </a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
    @else
    <div class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
        </path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun eleve</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aucun eleve n'est encore inscrit a cette formation.</p>
    </div>
    @endif
  </div>
</x-organisateur-layout>
