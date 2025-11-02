<div class="flex w-full items-center justify-between gap-3">
  {{-- Bouton Continuer vers la leçon --}}
  <a href="{{ route('eleve.lesson.show', [
        $team,
        $formation,
        $currentLesson->chapter,
        $currentLesson
    ]) }}" id="continue-button"
    class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
    {{-- Icône "Suivant" --}}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="w-6 h-6 mr-3">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
    </svg>
    {{ $currentLesson->title }}
    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </a>

  {{-- Toggle Autoplay --}}
  @if ($autoplay)
  <button type="button" wire:poll.1s="decrementCountdown" wire:click="autoplayOff"
    class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
    aria-live="polite">
    {{-- Icône "éclair" / auto --}}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="w-6 h-6 mr-3">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M11.412 15.655 9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 10.5h8.25l-4.707 5.043M8.457 8.457 3 3m5.457 5.457 7.086 7.086m0 0L21 21" />
    </svg>
    Cours suivant dans {{ $countdown }} seconde{{ $countdown > 1 ? 's' : '' }}
    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button>
  @else
  <button type="button" wire:click="autoplayOn"
    class="inline-flex items-center px-8 py-4 -bold text-lg rounded-xl transition-all duration-300  transform hover:-translate-y-1">
    {{-- Icône "éclair" / activer auto --}}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="w-6 h-6 mr-3">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
    </svg>
    

  </button>
  @endif
</div>

@push('scripts')
<script>
  // Quand le composant Livewire émet "autoplayRedirect", on clique sur le bouton "continuer".
  document.addEventListener('livewire:init', () => {
    if (window.Livewire && typeof Livewire.on === 'function') {
      Livewire.on('autoplayRedirect', () => {
        const btn = document.getElementById('continue-button');
        if (btn) btn.click();
      });
    }
  });
</script>
@endpush
