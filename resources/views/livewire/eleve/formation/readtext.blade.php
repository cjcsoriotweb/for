<div
    class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg" style="position:fixed;bottom:0;left:0;width:100%"
    wire:poll.1s="checkTimer"
>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Temps de lecture requis
        </h3>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <span>{{ $this->getRemainingTimeDisplayProperty() }}</span>
            / {{ $this->formatTime($requiredTime ?? 0) }}
        </div>
    </div>

    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
        <div
            class="h-2 rounded-full transition-all duration-1000 ease-out {{
                $canProceed ? 'bg-green-500' : 'bg-blue-500'
            }}"
            style="width: {{ $this->getProgressPercentageProperty() }}%"
        ></div>
    </div>

    <div class="text-center">
        @if($canProceed)

        <button
            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200"
        >
            ✅ Continuer au cours suivant
        </button>
        @endif
    </div>
</div>
