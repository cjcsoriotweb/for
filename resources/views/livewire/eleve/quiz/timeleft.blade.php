{{-- resources/views/livewire/eleve/quiz/timeleft.blade.php --}}
<div class="relative flex min-h-screen w-full flex-col items-center justify-center p-6">
  <div class="max-w-lg text-center">
    <x-heroicon-o-hourglass class="w-12 h-12 mx-auto text-gray-500" />
    <h2 class="mt-4 text-2xl font-bold text-[#111418] dark:text-white">Temps écoulé</h2>
    <p class="mt-2 text-gray-600 dark:text-gray-300">
      Le délai pour cette question est terminé. Passez à la suivante ou soumettez vos réponses.
    </p>
    <div class="mt-6 flex items-center justify-center gap-3">
      <button wire:click="nextQuestion"
        class="inline-flex items-center rounded-lg bg-primary px-5 py-2.5 text-white font-semibold hover:bg-primary/90">
        Question suivante
      </button>
      <button wire:click="validateQuiz"
        class="inline-flex items-center rounded-lg bg-gray-100 dark:bg-gray-700 px-5 py-2.5 text-gray-900 dark:text-white font-semibold hover:bg-gray-200 dark:hover:bg-gray-600">
        Soumettre
      </button>
    </div>
  </div>
</div>