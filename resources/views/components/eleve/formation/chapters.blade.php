@props(['formation', 'team' => null])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
  <div class="p-6 text-gray-900 dark:text-gray-100">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white">
        Contenu de la formation
      </h2>
      <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ $formation->chapters ? $formation->chapters->count() : 0 }} chapitre{{ ($formation->chapters ? $formation->chapters->count() : 0) > 1 ? 's' : '' }}
      </div>
    </div>

    @if($formation->chapters && $formation->chapters->count() > 0)
    <!-- Continue Button Section -->
    @php $studentFormationService =
    app(\App\Services\Formation\StudentFormationService::class);
    $currentLesson = $studentFormationService->getCurrentLesson($formation,
    auth()->user()); @endphp @if($currentLesson)

    <div class="mb-5">
      @livewire('eleve.formation.autoplay', [
          'formation' => $formation,
          'currentLesson' => $currentLesson,
          'team' => $team ?? auth()->user()?->currentTeam,
      ])
    </div>

    @endif

    <div class="space-y-4">
      @foreach($formation->chapters as $index => $chapter)
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <!-- Chapter Number -->
            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full text-blue-600 dark:text-blue-400 font-semibold text-sm">
              {{ $index + 1 }}
            </div>

            <!-- Chapter Info -->
            <div class="flex-1">
              <h3 class="font-semibold text-gray-900 dark:text-white {{ !$chapter->is_accessible ? 'text-gray-400 dark:text-gray-500' : '' }}">
                {{ is_string($chapter->title) ? $chapter->title : 'Chapitre' }}
              </h3>
              <div class="flex items-center space-x-2 mt-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                  {{ $chapter->lessons ? $chapter->lessons->count() : 0 }} leçon{{ ($chapter->lessons ? $chapter->lessons->count() : 0) > 1 ? 's' : '' }}
                </span>

                <!-- Status Badge -->
                @if($chapter->is_completed)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                  Terminé
                </span>
                @elseif($chapter->is_current)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                  </svg>
                  En cours
                </span>
                @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  Verrouillé
                </span>
                @endif
              </div>
            </div>
          </div>

          <!-- Action Button -->
          <div class="ml-4">
            @if($chapter->is_accessible && $chapter->lessons && $chapter->lessons->count() > 0)
            <a href="{{ route('eleve.lesson.show', [$team, $formation, $chapter, $chapter->lessons->first()]) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
              @if($chapter->is_current)
              Continuer
              @else
              Commencer
              @endif
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
            @else
            <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg cursor-not-allowed">
              Verrouillé
            </span>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-16">
      <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        Aucun chapitre disponible
      </h3>
      <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
        Cette formation n'a pas encore de chapitres. Revenez bientôt
        pour voir les nouveaux contenus.
      </p>
    </div>
    @endif
  </div>
</div>
