<div>
    <!-- Toggle Autoplay -->
    <div class="mb-4">
        @if($autoplay)
        <button
            wire:click="autoplayOff"
            class="text-sm text-red-600 hover:text-red-800 underline"
        >
            Désactiver l'autoplay
        </button>
        @else
        <button
            wire:click="autoplayOn"
            class="text-sm text-blue-600 hover:text-blue-800 underline"
        >
            Activer l'autoplay
        </button>
        @endif
    </div>

    @if($autoplay && $showCountdown)
    <!-- Countdown Display -->
    <div class="mb-6 text-center">
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full shadow-lg mb-4"
        >
            <span class="text-3xl font-bold text-white countdown-number">
                {{ $countdown }}
            </span>
        </div>
        <p class="text-gray-600 dark:text-gray-300">
            Redirection automatique dans {{ $countdown }} seconde{{
                $countdown > 1 ? "s" : ""
            }}...
        </p>
    </div>
    @endif

    <!-- Continue Button -->
    <a
        href="{{ route('eleve.lesson.show', [
                        request()->route('team'),
                        $formation,
                        $currentLesson->chapter,
                        $currentLesson
                    ]) }}"
        class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
        @if($autoplay)
        id="continue-button"
        @endif
    >
        <svg
            class="w-6 h-6 mr-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 8a9 9 0 110-18 9 9 0 010 18z"
            ></path>
        </svg>
        Continuer: {{ $currentLesson->title }}
        <svg
            class="w-6 h-6 ml-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
            ></path>
        </svg>
    </a>

    @if($autoplay)
    <!-- Hidden button for auto-click -->
    <button
        id="hidden-continue-button"
        class="hidden"
        onclick="document.getElementById('continue-button').click();"
    ></button>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', function () {
        @if($autoplay)
            // Start countdown when component loads if autoplay is enabled
            setTimeout(function() {
                @this.call('startCountdown');
            }, 500); // Small delay to ensure component is ready
        @endif
    });

    // Listen for Livewire events to handle countdown
    document.addEventListener('livewire:init', function () {
        Livewire.on('countdownTick', (countdownValue) => {
            const countdownNumber = document.querySelector('.countdown-number');
            if (countdownNumber) {
                countdownNumber.textContent = countdownValue;

                if (countdownValue > 0) {
                    // Schedule next tick after 1 second
                    setTimeout(() => {
                        @this.call('decrementCountdown');
                    }, 1000);
                }
            }
        });
    });
</script>
@endpush
