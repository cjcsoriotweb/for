<div wire:load="initModule" class="relative flex min-h-screen w-full flex-col items-center justify-center p-4">
  <div aria-live="polite" class="flex flex-col items-center justify-center" role="status">
    <div class="flex items-center justify-center space-x-2">
      <div class="h-4 w-4 rounded-full bg-primary animate-bubble-1"></div>
      <div class="h-4 w-4 rounded-full bg-primary animate-bubble-2"></div>
      <div class="h-4 w-4 rounded-full bg-primary animate-bubble-3"></div>
    </div>
    <p class="mt-4 text-lg font-medium text-slate-600 dark:text-slate-300">Getting your quiz
      ready...</p>
    <p class="mt-4 text-lg font-medium text-slate-600 dark:text-slate-300">
      <span wire:poll.1s="heartBeat">{{ uniqid() }}</span>
    </p>
  </div>
</div>