{{-- resources/views/livewire/eleve/quiz/question.blade.php --}}
@php
$q = $questions[$currentQuestionStep] ?? null;
$isMulti = $q ? in_array(strtolower($q->type), ['multiple_choice','multiple_choise','multiple-choice']) : false;
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
          <p class="text-[#111418] dark:text-white text-lg font-semibold mb-1">
            {{ $currentQuestionStep + 1 }} / {{ $questions->count() }}
            — {{ $q->question }}
          </p>
          <p class="text-xs text-gray-500 mb-4">
            Type : {{ $isMulti ? 'Choix multiples' : 'Vrai/Faux ou choix unique' }}
          </p>

          <div class="flex flex-col gap-3" role="{{ $isMulti ? 'group' : 'radiogroup' }}">
            @foreach($q->quizChoices as $choice)
            @php $selected = $this->isSelected($choice->id, $q->id); @endphp

            <label @if($selected) wire:click="unSelectReponse({{ $q->id }}, {{ $choice->id }})" @else
              wire:click="selectReponse({{ $choice->id }})" @endif role="{{ $isMulti ? 'checkbox' : 'radio' }}"
              aria-checked="{{ $selected ? 'true' : 'false' }}"
              @class([ 'flex items-center gap-4 rounded-lg border p-4 cursor-pointer transition' , $selected
              ? 'border-blue-700 bg-blue-600 text-white hover:bg-blue-600 focus:ring-2 focus:ring-blue-600'
              : 'border-gray-300 dark:border-gray-700 hover:bg-primary/10 dark:hover:bg-primary/20 text-[#111418] dark:text-gray-200'
              ])>
              <div class="shrink-0">
                @if($selected)
                @if($isMulti)
                <x-heroicon-o-check-badge class="w-6 h-6 text-white" />
                @else
                <x-heroicon-o-check-circle class="w-6 h-6 text-white" />
                @endif
                @else
                @if($isMulti)
                <span class="inline-block w-5 h-5 rounded-md border border-gray-400"></span>
                @else
                <span class="inline-block w-5 h-5 rounded-full border border-gray-400"></span>
                @endif
                @endif
              </div>
              <span class="text-sm font-medium">
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
        @if(count($this->reponse) > 0)

        <button wire:click="nextQuestion"
          class="inline-flex items-center justify-center rounded-lg h-12 px-5 bg-primary text-white font-bold hover:bg-primary/90">
          Suivant
        </button>
        @endif

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