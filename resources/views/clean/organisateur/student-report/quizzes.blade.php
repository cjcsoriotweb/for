<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
  <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Historique des quiz</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
      Détail des tentatives enregistrées pour les évaluations de la formation.
    </p>
  </div>
  <div class="px-4 py-5 sm:px-6">
    @if($quizAttempts->count() > 0)
    <div class="space-y-4">
      @foreach($quizAttempts as $attempt)
      <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-3">
          <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $attempt->lesson->title }}</h4>
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $attempt->created_at->format('d/m/Y H:i') }}
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
          <div>
            <span class="font-medium text-gray-500 dark:text-gray-400">Score :</span>
            <span class="ml-2 text-gray-900 dark:text-white">
              {{ $attempt->score ? round($attempt->score, 1) : 0 }}%
            </span>
          </div>

          <div>
            <span class="font-medium text-gray-500 dark:text-gray-400">Temps passé :</span>
            <span class="ml-2 text-gray-900 dark:text-white">
              {{ $attempt->duration_seconds
                ? floor($attempt->duration_seconds / 60).'min '.($attempt->duration_seconds % 60).'s'
                : 'N/A' }}
            </span>
          </div>

          <div>
            <span class="font-medium text-gray-500 dark:text-gray-400">Réponses :</span>
            <span class="ml-2 text-gray-900 dark:text-white">
              {{ $attempt->answers->count() }}/{{ optional(optional($attempt->lesson->lessonable)->quizQuestions)->count() ?? 0 }}
            </span>
          </div>
        </div>

        @if($attempt->answers->count() > 0)
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Réponses détaillées :</p>
          <div class="space-y-3">
            @foreach($attempt->answers as $answer)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
              <div class="flex items-start">
                <span
                  class="w-6 h-6 rounded-full {{ $answer->is_correct ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }} flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  {{ $answer->is_correct ? '✔' : '✖' }}
                </span>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                    {{ optional($answer->question)->question ?? 'Question non trouvée' }}
                  </p>

                  @if($answer->choice)
                  <div class="text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Réponse choisie :</span>
                    <span
                      class="ml-1 {{ $answer->is_correct ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                      {{ $answer->choice->choice_text }}
                    </span>
                  </div>
                  @endif

                  @if(optional($answer->question)->quizChoices)
                  <div class="text-xs mt-1">
                    <span class="text-gray-500 dark:text-gray-400">Réponses correctes :</span>
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
    @else
    <p class="text-gray-500 dark:text-gray-400">Aucune tentative de quiz n’a encore été enregistrée pour cet élève.</p>
    @endif
  </div>
</div>
