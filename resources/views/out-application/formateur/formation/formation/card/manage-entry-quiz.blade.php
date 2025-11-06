<!-- Entry Quiz Management Card -->
<div class="bg-gradient-to-br from-purple-50 to-indigo-50 overflow-hidden shadow-lg sm:rounded-xl border border-purple-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
  <div class="p-6">
    <div class="flex items-center mb-6">
      <div class="flex-shrink-0">
        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <div class="ml-4">
        <h3 class="text-xl font-bold text-gray-900 bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
          Quiz d'entrée
        </h3>
        <p class="text-sm text-gray-600 mt-1">Évaluation préalable du niveau des apprenants</p>
      </div>
    </div>

    @php
      $entryQuiz = $formation->entryQuiz;
    @endphp

    @if($entryQuiz)
      <div class="mb-6 p-4 bg-white/70 backdrop-blur-sm rounded-xl border border-purple-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">{{ $entryQuiz->title }}</h4>
              <p class="text-sm text-gray-600">{{ $entryQuiz->description ?? 'Aucune description' }}</p>
            </div>
          </div>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-sm">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Actif
          </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-3 rounded-lg">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <div>
                <p class="text-xs text-blue-600 font-medium">Questions</p>
                <p class="text-lg font-bold text-blue-900">{{ $entryQuiz->quizQuestions->count() }}</p>
              </div>
            </div>
          </div>
          <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-3 rounded-lg">
            <div class="flex items-center">
              <svg class="w-4 h-4 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              <div>
                <p class="text-xs text-amber-600 font-medium">Niveau cible</p>
                <p class="text-lg font-bold text-amber-900">
                  {{ $entryQuiz->entry_min_score ?? 0 }}% &ndash; {{ $entryQuiz->entry_max_score ?? ($entryQuiz->passing_score ?? 100) }}%
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border-2 border-dashed border-gray-200">
        <div class="text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h4 class="text-sm font-medium text-gray-900 mb-1">Aucun quiz d'entrée</h4>
          <p class="text-xs text-gray-500">Créez un quiz pour évaluer le niveau de vos apprenants</p>
        </div>
      </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3">
      @if($entryQuiz)
        <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
           class="flex-1 inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-purple-700 bg-white hover:bg-purple-50 rounded-xl border border-purple-200 transition-all duration-200 shadow-sm hover:shadow-md">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Gérer les questions
        </a>
        <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
           class="flex-1 inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 rounded-xl border border-gray-200 transition-all duration-200 shadow-sm hover:shadow-md">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Configuration
        </a>
      @else
        <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
           class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Créer un quiz d'entrée
        </a>
      @endif
    </div>
  </div>
</div>
