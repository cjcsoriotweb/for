<!-- Entry Quiz Management Card -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
  <div class="p-6">
    <div class="flex items-center mb-4">
      <div class="flex-shrink-0">
        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
      <div class="ml-4">
        <h3 class="text-lg font-semibold text-gray-900">Quiz d'entrée</h3>
        <p class="text-sm text-gray-600">Évaluez le niveau des apprenants avant l'accès à la formation</p>
      </div>
    </div>

    @php
      $entryQuiz = $formation->entryQuiz;
    @endphp

    @if($entryQuiz)
      <div class="mb-4 p-3 bg-purple-50 rounded-lg">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="font-medium text-purple-900">{{ $entryQuiz->title }}</h4>
            <p class="text-sm text-purple-700">
              Score requis: {{ $entryQuiz->passing_score }}% |
              Questions: {{ $entryQuiz->quizQuestions->count() }}
            </p>
          </div>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            Configuré
          </span>
        </div>
      </div>
    @else
      <div class="mb-4 p-3 bg-gray-50 rounded-lg">
        <p class="text-sm text-gray-600">Aucun quiz d'entrée configuré</p>
      </div>
    @endif

    <div class="flex space-x-2">
      @if($entryQuiz)
        <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-purple-700 bg-purple-100 hover:bg-purple-200 rounded-lg transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Gérer les questions
        </a>
        <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Configuration
        </a>
      @else
        <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors duration-200">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Créer un quiz d'entrée
        </a>
      @endif
    </div>
  </div>
</div>
