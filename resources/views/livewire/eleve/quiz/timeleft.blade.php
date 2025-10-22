{{-- resources/views/livewire/eleve/quiz/timeleft.blade.php --}}
<div class="relative flex min-h-screen w-full flex-col items-center justify-center p-6">
  <div class="max-w-lg text-center">
    <h2 class="mt-4 text-2xl font-bold text-[#111418] dark:text-white">Temps écoulé</h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
      Le délai pour cette question est terminé.
    </p>
    <div class="flex items-center justify-center space-x-2">
      <div class="h-3 w-3 rounded-full bg-primary animate-bounce"></div>
      <div class="h-3 w-3 rounded-full bg-primary animate-pulse [animation-delay:0.15s]"></div>
      <div class="h-3 w-3 rounded-full bg-primary animate-bounce [animation-delay:0.3s]"></div>
    </div>
    <div wire:poll.5s="nextQuestion" class="mt-6 flex items-center justify-center gap-3"></div>
  </div>
</div>