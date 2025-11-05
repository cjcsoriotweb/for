@props(['team', 'formation', 'progress'])

<!-- Actions Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-5">
  <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">
    Actions rapides
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <!-- Continue Learning -->
    @php
    $studentFormationService = app(\App\Services\Formation\StudentFormationService::class);
    $currentLesson = $studentFormationService->getCurrentLesson($formation, auth()->user());
    @endphp
    @if($currentLesson && $currentLesson->chapter)
    <a href="{{ route('eleve.lesson.show', [$team, $formation, $currentLesson->chapter, $currentLesson]) }}"
       class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 border border-blue-200 dark:border-blue-800">
      <div class="flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l.707.707A1 1 0 0012.414 11H15m2 0h1.586a1 1 0 01.707.293l.707.707A1 1 0 0021 12.414V15m-6 6h1.586a1 1 0 01.707.293l.707.707A1 1 0 0019 21.414V15"></path>
        </svg>
      </div>
      <div class="ml-2">
        <p class="text-sm font-medium">Continuer l'apprentissage</p>
        <p class="text-xs text-blue-600 dark:text-blue-400">Reprendre où vous en étiez</p>
      </div>
    </a>
    @endif

    <!-- Formation Resources -->
    @if(isset($formationDocuments) && $formationDocuments->isNotEmpty() || isset($lessonResources) && $lessonResources->isNotEmpty())
    <button onclick="document.getElementById('resources-section')?.scrollIntoView({behavior: 'smooth'})"
            class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg transition-all duration-200 border border-purple-200 dark:border-purple-800">
      <div class="flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
      </div>
      <div class="ml-2">
        <p class="text-sm font-medium">Voir les ressources</p>
        <p class="text-xs text-purple-600 dark:text-purple-400">Documents et fichiers</p>
      </div>
    </button>
    @endif

    <!-- Back to Dashboard -->
    <a href="{{ route('eleve.index', $team) }}"
       class="flex items-center p-3 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 border border-gray-200 dark:border-gray-600">
      <div class="flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
      </div>
      <div class="ml-2">
        <p class="text-sm font-medium">Retour au tableau de bord</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">Voir toutes vos formations</p>
      </div>
    </a>
  </div>
</div>
