<div>
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
      <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div
            class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center flex-1">
            <span class="material-symbols-outlined text-red-500 text-8xl mb-4">arrow-down-left</span>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Time's Up!</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">You did not provide an answer in time.</p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-colors">
                <span class="truncate">Try Again</span>
              </button>
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-colors">
                <span class="truncate">View Results</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
      <div class="px-4 md:px-10 lg:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap justify-between items-center gap-4 p-4 text-center">
            <h1 class="text-[#111418] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em] min-w-72">
              General Knowledge Quiz</h1>
            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
              <span class="material-symbols-outlined text-primary text-3xl">timer</span>
              <div class="flex flex-col items-start">
                <span class="text-xs text-gray-500 dark:text-gray-400">Time Left</span>
                <span class="text-2xl font-bold text-[#111418] dark:text-white" wire:poll.1s="countdownPoll">{{
                  $countdown }}</span>
              </div>
            </div>
          </div>
          <div class="space-y-6 mt-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 md:p-8">
              <p class="text-[#111418] dark:text-white text-lg font-semibold mb-4">1. What is the capital of France?</p>
              <div class="flex flex-col gap-3">
                <label
                  class="flex items-center gap-4 rounded-lg border border-solid border-[#dbe0e6] dark:border-gray-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <input
                    class="h-5 w-5 border-2 border-[#dbe0e6] dark:border-gray-600 bg-transparent text-transparent checked:border-primary checked:bg-[image:--radio-dot-svg] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                    name="question-1" type="radio" />
                  <span class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">Paris</span>
                </label>
                <label
                  class="flex items-center gap-4 rounded-lg border border-solid border-[#dbe0e6] dark:border-gray-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <input
                    class="h-5 w-5 border-2 border-[#dbe0e6] dark:border-gray-600 bg-transparent text-transparent checked:border-primary checked:bg-[image:--radio-dot-svg] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                    name="question-1" type="radio" />
                  <span class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">London</span>
                </label>
                <label
                  class="flex items-center gap-4 rounded-lg border border-solid border-[#dbe0e6] dark:border-gray-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <input
                    class="h-5 w-5 border-2 border-[#dbe0e6] dark:border-gray-600 bg-transparent text-transparent checked:border-primary checked:bg-[image:--radio-dot-svg] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                    name="question-1" type="radio" />
                  <span class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">Berlin</span>
                </label>
                <label
                  class="flex items-center gap-4 rounded-lg border border-solid border-[#dbe0e6] dark:border-gray-700 p-[15px] hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer transition-colors">
                  <input
                    class="h-5 w-5 border-2 border-[#dbe0e6] dark:border-gray-600 bg-transparent text-transparent checked:border-primary checked:bg-[image:--radio-dot-svg] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 focus:ring-offset-background-light dark:focus:ring-offset-background-dark"
                    name="question-1" type="radio" />
                  <span class="text-[#111418] dark:text-gray-300 text-sm font-medium leading-normal">Madrid</span>
                </label>
              </div>
            </div>
          </div>
          <div class="flex px-4 py-3 mt-6">
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 flex-1 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-background-light dark:focus:ring-offset-background-dark transition-colors">
              <span class="truncate">Submit</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>