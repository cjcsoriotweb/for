{{-- resources/views/livewire/eleve/quiz/reponse.blade.php --}}
<div
  class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark p-4 sm:p-6 lg:p-8">
  <div class="w-full max-w-4xl">
    <div class="text-center">
      <p class="text-4xl font-black tracking-tighter text-[#111418] dark:text-white sm:text-5xl">
        Merci pour vos réponses<br>regardons le résultat.
      </p>
    </div>

    <div class="mt-8 flex flex-col gap-6">


      @foreach($questions as $q)
      @php
      $qid = (int) $q->id;
      $isMulti = in_array(strtolower($q->type), ['multiple_choice','multiple_choise','multiple-choice']);
      $selectedRaw = $reponse[$qid] ?? ($isMulti ? [] : null);

      // Normaliser en tableau d'IDs sélectionnés
      $selectedIds = $isMulti
      ? collect($selectedRaw)->map(fn($v)=>(int)$v)->unique()->values()->all()
      : (isset($selectedRaw) ? [(int)$selectedRaw] : []);

      // Modèles choisis par l’utilisateur
      $selectedModels = $q->quizChoices->whereIn('id', $selectedIds)->values();

      // Ensemble des bonnes réponses
      $correctIds = $q->quizChoices->where('is_correct', true)->pluck('id')->map(fn($v)=>(int)$v)->values()->all();

      // Calcul exact-match pour l’icône et l’état
      $selectedSorted = $selectedIds; sort($selectedSorted);
      $expectedSorted = $correctIds; sort($expectedSorted);
      $isCorrect = !empty($expectedSorted) && ($selectedSorted === $expectedSorted);

      // Pour affichage "Bonne réponse : …"
      $goodModels = $q->quizChoices->where('is_correct', true)->values();
      @endphp

      <div
        class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark p-4 sm:p-6">
        <div class="flex items-start gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
          <div
            class="flex size-12 shrink-0 items-center justify-center rounded-lg {{ $isCorrect ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
            @if($isCorrect)
            <x-heroicon-o-check-circle class="w-6 h-6" />
            @else
            <x-heroicon-o-exclamation-circle class="w-6 h-6" />
            @endif
          </div>

          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
              {{ $q->question }}
            </p>

            <p class="text-sm text-gray-700 dark:text-gray-300">
              Votre réponse :
              @if($selectedModels->isEmpty())
              <span class="font-medium">—</span>
              @else
              <span class="font-medium">
                {{ $selectedModels->pluck('choice_text')->join(', ') }}
              </span>
              @endif
            </p>

            @if(!$isCorrect && $goodModels->isNotEmpty())
            <p class="mt-1 text-sm text-green-700 dark:text-green-300">
              Bonne réponse : <span class="font-medium">{{ $goodModels->pluck('choice_text')->join(', ') }}</span>
            </p>
            @endif
          </div>

          @unless($isCorrect)
          <div class="hidden shrink-0 sm:block">
            <p class="text-sm font-medium text-red-600">Mauvaise réponse</p>
          </div>
          @endunless
        </div>
      </div>
      @endforeach


    </div>

    <div class="flex items-center justify-center gap-3 pt-8">
      <button wire:click="launchQuiz"
        class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-primary text-white font-bold hover:bg-primary/90">
        Recommencer le quiz
      </button>
      <button wire:click="setStep({{\App\Livewire\Eleve\QuizComponentO::STEP_LOADING}})"
        class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold hover:bg-gray-200 dark:hover:bg-gray-600">
        Retour à l’accueil
      </button>
    </div>
  </div>
</div>