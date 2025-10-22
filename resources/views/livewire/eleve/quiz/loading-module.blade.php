<div wire:init="heartBeat" class="relative flex min-h-screen w-full flex-col items-center justify-center p-4">
  <div aria-live="polite" class="flex flex-col items-center justify-center" role="status">
    <div class="flex items-center justify-center space-x-2">
      <div class="h-3 w-3 rounded-full bg-primary animate-bounce"></div>
      <div class="h-3 w-3 rounded-full bg-primary animate-pulse [animation-delay:0.15s]"></div>
      <div class="h-3 w-3 rounded-full bg-primary animate-bounce [animation-delay:0.3s]"></div>
    </div>
    <p class="mt-4 text-lg font-medium text-slate-600 dark:text-slate-300">
      Préparation du quiz…
    </p>
    <p class="mt-2 text-sm font-medium text-slate-600 dark:text-slate-300">
      <span wire:poll.1s="heartBeat">
        {{ min($currentQuestionStep + 1, max(1, $questions->count())) }} / {{ $questions->count() }}
      </span>
    </p>

    <button wire:click="launchQuiz"
      class="mt-6 inline-flex items-center rounded-lg bg-primary px-5 h-11 text-white font-semibold hover:bg-primary/90">
      Commencer maintenant
    </button>
  </div>
</div>