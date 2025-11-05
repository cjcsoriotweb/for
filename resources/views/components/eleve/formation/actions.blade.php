@props(['team', 'formation', 'progress'])

<!-- Actions Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-5">
  <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
    Actions rapides
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Continue Learning -->
    @php
    $studentFormationService = app(\App\Services\Formation\StudentFormationService::class);
    $currentLesson = $studentFormationService->getCurrentLesson($formation, auth()->user());
    @endphp
    @if($currentLesson && $currentLesson->chapter)
    <a href="{{ route('eleve.lesson.show', [$team, $formation, $currentLesson->chapter, $currentLesson]) }}"
       class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
      <div class="flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l.707.707A1 1 0 0012.414 11H15m2 0h1.586a1 1 0 01.707.293l.707.707A1 1 0 0021 12.414V15m-6 6h1.586a1 1 0 01.707.293l.707.707A1 1 0 0019 21.414V15"></path>
        </svg>
      </div>
      <div class="ml-3">
        <p class="font-semibold">Continuer l'apprentissage</p>
        <p class="text-sm text-blue-100">Reprendre où vous en étiez</p>
      </div>
    </a>
    @endif

    <!-- Formation Resources -->
    @if(isset($formationDocuments) && $formationDocuments->isNotEmpty() || isset($lessonResources) && $lessonResources->isNotEmpty())
    <button onclick="document.getElementById('resources-section')?.scrollIntoView({behavior: 'smooth'})"
            class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
      <div class="flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
      </div>
      <div class="ml-3">
        <p class="font-semibold">Voir les ressources</p>
        <p class="text-sm text-purple-100">Documents et fichiers</p>
      </div>
    </button>
    @endif



    <!-- Back to Dashboard -->
    <a href="{{ route('eleve.index', $team) }}"
       class="flex items-center p-4 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
      <div class="flex-shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
      </div>
      <div class="ml-3">
        <p class="font-semibold">Retour au tableau de bord</p>
        <p class="text-sm text-gray-200">Voir toutes vos formations</p>
      </div>
    </a>
  </div>
</div>
