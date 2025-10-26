<div class="flex">
  <!-- Toggle Autoplay -->
  <div class="mb-4">
    @if($autoplay)


    <a wire:poll.1s="decrementCountdown" wire:click="autoplayOff"
      class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
      @if($autoplay) id="continue-button" @endif>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M11.412 15.655 9.75 21.75l3.745-4.012M9.257 13.5H3.75l2.659-2.849m2.048-2.194L14.25 2.25 12 10.5h8.25l-4.707 5.043M8.457 8.457 3 3m5.457 5.457 7.086 7.086m0 0L21 21" />
      </svg>

      Désactiver l'autoplay {{ $countdown }} seconde{{
      $countdown > 1 ? "s" : ""
      }}
      <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </a>
    @else

    <!-- Continue Button -->
    <a wire:click="autoplayOn"
      class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor"
        className="size-6">
        <path strokeLinecap="round" strokeLinejoin="round"
          d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
      </svg>

      Automatique
      <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </a>
    @endif
  </div>


  <!-- Continue Button -->
  <a href="{{ route('eleve.lesson.show', [
                        auth()->user()->currentTeam ?? $formation->teams()->first(),
                        $formation,
                        $currentLesson->chapter,
                        $currentLesson
                    ]) }}"
    class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
    @if($autoplay) id="continue-button" @endif>
    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 8a9 9 0 110-18 9 9 0 010 18z"></path>
    </svg>
    Continuer: {{ $currentLesson->title }}
    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
    </svg>
  </a>

  @if($autoplay)
  <!-- Hidden button for auto-click -->
  <button id="hidden-continue-button" class="hidden"
    onclick="document.getElementById('continue-button').click();"></button>
  @endif
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Listen for Livewire events to handle countdown completion
    document.addEventListener("livewire:init", function () {
      Livewire.on("autoplayRedirect", function () {
        const continueButton =
          document.getElementById("continue-button");
        if (continueButton) {
          continueButton.click();
        }
      });
    });
  });
</script>
@endpush