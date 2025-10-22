{{-- resources/views/livewire/eleve/quiz/question.blade.php --}}
@php
$q = $questions[$currentQuestionStep] ?? null;
@endphp

<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
  <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
    <div class="flex flex-col max-w-[960px] flex-1">
      <div class="flex flex-wrap justify-between items-center gap-4 p-4">
        <h1 class="text-[#111418] dark:text-white text-3xl md:text-4xl font-black tracking-[-0.03em]">
          General Knowledge Quiz
        </h1>
        <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
          <x-heroicon-o-clock class="w-8 h-8 text-gray-500" />
          <div class="flex flex-col items-start">
            <span class="text-xs text-gray-500 dark:text-gray-400">Temps restant</span>
            <span class="text-2xl font-bold text-[#111418] dark:text-white" wire:poll.1s="dIntCountdown">
              {{ $countdown }}
            </span>
          </div>
        </div>
      </div>

      <div class="space-y-6 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 md:p-8">
          @if($q)
          <p class="text-[#111418] dark:text-white text-lg font-semibold mb-4">
            {{ $currentQuestionStep + 1 }} / {{ $questions->count() }} — {{ $q->question }}
          </p>

          <div class="flex flex-col gap-3">
            @foreach($q->quizChoices as $choice)
            @php $selected = $this->isSelected($choice->id, $q->id); @endphp

            <label @if($selected) wire:click="unSelectReponse({{ $q->id }})" @else
              wire:click="selectReponse({{ $choice->id }})" @endif class="flex items-center gap-4 rounded-lg border p-4 cursor-pointer transition-colors
                        {{ $selected
                            ? 'border-blue-700 bg-primary/10 dark:bg-primary/20'
                            : 'border-gray-300 hover:bg-primary/10 dark:border-gray-700 dark:hover:bg-primary/20' }}">
              <div class="shrink-0">
                @if($selected)
                <x-heroicon-o-check-circle class="w-6 h-6 text-blue-600" />
                @else
                <span class="inline-block w-6 h-6 rounded-full border border-gray-400"></span>
                @endif
              </div>
              <span class="text-[#111418] dark:text-gray-200 text-sm font-medium">
                {{ $choice->choice_text }}
              </span>
            </label>
            @endforeach
          </div>
          @else
          <p class="text-sm text-gray-500">Aucune question disponible.</p>
          @endif
        </div>
      </div>

      <div class="flex gap-3 px-4 py-3 mt-6">
        <button wire:click="prevQuestion"
          class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white font-semibold hover:bg-gray-200 dark:hover:bg-gray-600">
          Précédent
        </button>

        @if($this->hasNextQuestion())
        <button wire:click="nextQuestion"
          class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-primary text-white font-bold hover:bg-primary/90">
          Suivant
        </button>
        @else
        <button wire:click="validateQuiz"
          class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-primary text-white font-bold hover:bg-primary/90">
          Soumettre
        </button>
        @endif
      </div>
    </div>
  </div>
</div>