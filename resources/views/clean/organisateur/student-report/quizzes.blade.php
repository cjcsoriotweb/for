<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
  <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Historique des quiz</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Détail des tentatives enregistrées pour les évaluations de
      la formation.</p>
  </div>

  <div class="px-4 py-5 sm:px-6">
    @if ($quizAttempts->count() > 0)
    <div class="space-y-4">
      @foreach ($quizAttempts as $attempt)
      <div
        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 transition hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-3">
          <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $attempt->lesson->title }}</h4>
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ $attempt->created_at->format('d/m/Y H:i') }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-500 dark:text-gray-400">Score :</span>
            <span class="text-gray-900 dark:text-white">{{ $attempt->score !== null ? round($attempt->score, 1) : 0
              }}%</span>
          </div>

          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-500 dark:text-gray-400">Temps passé :</span>
            <span class="text-gray-900 dark:text-white">{{ $attempt->duration_seconds ? floor($attempt->duration_seconds
              / 60) . 'min ' . ($attempt->duration_seconds % 60) . 's' : 'N/A' }}</span>
          </div>

          <div class="flex items-center gap-2">
            <span class="font-medium text-gray-500 dark:text-gray-400">Réponses :</span>
            <span class="text-gray-900 dark:text-white">{{ $attempt->answers->count() }}/{{
              optional(optional($attempt->lesson->lessonable)->quizQuestions)->count() ?? 0 }}</span>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Réponses détaillées</p>

          @if ($attempt->answers->count() > 0)
          <div class="space-y-3">
            @foreach ($attempt->answers as $answer)
            <div
              class="rounded-lg border-l-4 {{ $answer->is_correct ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-red-500 bg-red-50 dark:bg-red-900/20' }} p-4">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                  <p class="text-sm font-semibold text-gray-900 dark:text-white">{{
                    optional($answer->question)->question ?? 'Question non trouvée' }}</p>

                  @if ($answer->choice)
                  <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-medium text-gray-500 dark:text-gray-400">Réponse choisie :</span>
                    <span
                      class="ml-1 inline-flex items-center px-2 py-0.5 rounded {{ $answer->is_correct ? 'bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-200 text-red-800 dark:bg-red-800 dark:text-red-100' }}">{{
                      $answer->choice->choice_text }}</span>
                  </p>
                  @else
                  <p class="mt-2 text-xs italic text-gray-500 dark:text-gray-400">Aucune réponse n'a été sélectionnée
                    pour cette question.</p>
                  @endif
                </div>

                <span
                  class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $answer->is_correct ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-100' }}">{{
                  $answer->is_correct ? 'Bonne réponse' : 'Réponse incorrecte' }}</span>
              </div>

              @if (optional($answer->question)->quizChoices)
              <div class="mt-3 text-xs text-gray-600 dark:text-gray-300">
                <span class="font-medium text-gray-500 dark:text-gray-400">Réponses correctes :</span>
                <div class="mt-1 flex flex-wrap gap-2">
                  @foreach ($answer->question->quizChoices->where('is_correct', true) as $correctChoice)
                  <span
                    class="inline-flex items-center rounded px-2 py-0.5 bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">{{
                    $correctChoice->choice_text }}</span>
                  @endforeach
                </div>
              </div>
              @endif
            </div>
            @endforeach
          </div>
          @else
          <p class="text-sm italic text-gray-500 dark:text-gray-400">Aucune réponse n'a été enregistrée pour cette
            tentative.</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @else
    <p class="text-gray-500 dark:text-gray-400">Aucune tentative de quiz n'a encore été enregistrée pour cet élève.</p>
    @endif
  </div>
</div>