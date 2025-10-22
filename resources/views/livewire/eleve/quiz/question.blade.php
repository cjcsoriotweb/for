<div>

  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
      <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap justify-between items-center gap-4 p-4 text-center">
            <h1 class="text-[#111418] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">
              General Knowledge Quiz</h1>
            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
              <x-heroicon-o-clock class="w-10 h-10 text-gray-500" />
              <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 dark:text-gray-400">Temps restant</span>
                <span class="text-2xl font-bold text-[#111418] dark:text-white" wire:poll.1s="dIntCountdown">
                  {{ $countdown }}</span>
              </div>
            </div>
          </div>
          <div class="space-y-6 mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 md:p-8">
              <p class="text-[#111418] dark:text-white text-lg font-semibold mb-4">{{$currentQuestionStep}}. {{
                $questions[$currentQuestionStep]->question }}</p>
              <div class="flex flex-col gap-3">

                @foreach($questions[$currentQuestionStep]->quizChoices as $choice)
                @if(isset($this->reponse[$choice->id]))
                <label wire:click="unSelectReponse('{{$choice->id}}')"
                  class="flex items-center gap-4 rounded-lg border border-solid border-blue-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <span
                    class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">{{$choice->choice_text}}
                  </span>
                </label>
                @else
                <label wire:click="selectReponse('{{$choice->id}}')"
                  class="flex items-center gap-4 rounded-lg border border-solid border-grey-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <span
                    class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">{{$choice->choice_text}}
                  </span>
                </label>
                @endif
                @endforeach

              </div>
            </div>
          </div>
          <div class="flex px-4 py-3 mt-6">
            <button wire:click="validateQuiz"
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 flex-1 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-colors">
              <span class="truncate">Submit</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>