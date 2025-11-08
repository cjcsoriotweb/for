<div class="flex w-full items-center justify-between gap-3">
  {{-- Bouton vers le module suivant --}}
  @if($currentLesson && $currentLesson->chapter)
  <button type="button" wire:click="proceedToLesson"
    class="inline-flex items-center justify-between w-full px-4 py-3 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-xl border border-blue-200 dark:border-blue-800 transition-colors">
    <div class="text-left">
      <div class="text-sm font-semibold">Module suivant</div>
      <div class="text-xs text-blue-600/80 dark:text-blue-200/80">
        {{ method_exists($currentLesson, 'getName') ? $currentLesson->getName() : $currentLesson->title }}
      </div>
    </div>
    <svg class="w-4 h-4 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button>
  @else
  <span class="inline-flex items-center px-4 py-2 bg-gray-50 dark:bg-gray-800 text-gray-400 dark:text-gray-500 font-medium text-sm rounded-lg border border-gray-200 dark:border-gray-600 cursor-not-allowed">
    Aucune leçon disponible
  </span>
  @endif

  {{-- Toggle Autoplay --}}
  @if ($autoplay)
  <button type="button" wire:poll.1s="decrementCountdown" wire:click="autoplayOff"
    class="inline-flex items-center px-3 py-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-700 dark:text-red-300 font-medium text-sm rounded-lg border border-red-200 dark:border-red-800 transition-colors"
    aria-live="polite">
    {{-- Icône "éclair" / auto --}}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="w-4 h-4 mr-2">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M11.412 15.655 9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 10.5h8.25l-4.707 5.043M8.457 8.457 3 3m5.457 5.457 7.086 7.086m0 0L21 21" />
    </svg>
    Arrêter auto ({{ $countdown }}s)
  </button>
  @else
  <button type="button" wire:click="autoplayOn"
    class="inline-flex items-center px-3 py-2 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 text-green-700 dark:text-green-300 font-medium text-sm rounded-lg border border-green-200 dark:border-green-800 transition-colors">
    {{-- Icône "éclair" / activer auto --}}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="w-4 h-4 mr-2">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
    </svg>
    Lecture auto
  </button>
  @endif
</div>
