<div
  class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden p-4 sm:p-6 lg:p-8">
  <div class="layout-container flex h-full w-full max-w-4xl grow flex-col">
    <div class="layout-content-container flex flex-col gap-8 py-10">
      <div class="flex flex-col gap-2 text-center">
        <p class="text-4xl font-black tracking-tighter text-[#111418] dark:text-white sm:text-5xl">Merci pour vos
          réponses <br> regardons le résultat.</p>
        <p class="text-lg font-medium text-gray-600 dark:text-gray-400">Votre score : 7 / 10</p>
      </div>
      <div
        class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-background-dark p-4 sm:p-6">
        <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
          <div
            class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400">
            <span class="material-symbols-outlined text-2xl">
              <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
            </span>
          </div>
          <div class="flex flex-1 flex-col justify-center">
            <p class="text-base font-medium text-[#111418] dark:text-white line-clamp-1">Question 1: What is the capital
              of France?</p>
            <p class="text-sm font-normal text-gray-600 dark:text-gray-400 line-clamp-2">Votre réponse : Paris</p>
          </div>
          <div class="hidden shrink-0 sm:block">
            <p class="text-sm font-medium text-green-600 dark:text-green-400">Correct</p>
          </div>
        </div>
        <div class="flex gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
          <div
            class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
            <span class="material-symbols-outlined text-2xl">
              <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" />
            </span>
          </div>
          <div class="flex flex-1 flex-col justify-center gap-1">
            <p class="text-base font-medium text-[#111418] dark:text-white">Question 2: What is 2 + 2?</p>
            <p class="text-sm font-normal text-red-600 dark:text-red-400">Votre réponse : 5</p>
            <p class="text-sm font-normal text-green-600 dark:text-green-400">Réponse correcte : 4</p>
          </div>
          <div class="hidden shrink-0 sm:block">
            <p class="text-sm font-medium text-red-600 dark:text-red-400">Incorrect</p>
          </div>
        </div>
        <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
          <div
            class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400">
            <span class="material-symbols-outlined text-2xl">
              <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" />
            </span>
          </div>
          <div class="flex flex-1 flex-col justify-center">
            <p class="text-base font-medium text-[#111418] dark:text-white line-clamp-1">Question 3: What is the largest
              planet in our solar system?</p>
            <p class="text-sm font-normal text-gray-600 dark:text-gray-400 line-clamp-2">Votre réponse : Jupiter</p>
          </div>
          <div class="hidden shrink-0 sm:block">
            <p class="text-sm font-medium text-green-600 dark:text-green-400">Correct</p>
          </div>
        </div>
        <div class="flex gap-4">
          <div
            class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400">
            <span class="material-symbols-outlined text-2xl">
              <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" />
            </span>
          </div>
          <div class="flex flex-1 flex-col justify-center gap-1">
            <p class="text-base font-medium text-[#111418] dark:text-white">Question 4: Who wrote 'To Kill a
              Mockingbird'?</p>
            <p class="text-sm font-normal text-red-600 dark:text-red-400">Votre réponse : Stephen King</p>
            <p class="text-sm font-normal text-green-600 dark:text-green-400">Réponse correcte : Harper Lee</p>
          </div>
          <div class="hidden shrink-0 sm:block">
            <p class="text-sm font-medium text-red-600 dark:text-red-400">Incorrect</p>
          </div>
        </div>
      </div>
      <div class="flex flex-col items-center justify-center pt-6 text-center">
        <p class="mb-4 text-lg font-medium text-gray-600 dark:text-gray-400" wire:poll.1s="setStep(1)">Redemarre en
          cours</p>
        <div class="relative flex h-24 w-24 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
          <div class="absolute inset-0 rounded-full border-4 border-primary animate-pulse"></div>
          <span class="text-5xl font-bold text-primary countdown"></span>
        </div>
      </div>
    </div>
  </div>
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>
</div>