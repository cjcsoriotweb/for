<div wire:poll.2s>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Progression de lecture</h4>
            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $readPercent }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div
                class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out"
                style="width: {{ $readPercent }}%"
            ></div>
        </div>
        @if($readPercent >= 100)
            <div class="flex items-center space-x-2 mt-3 text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Lecture terminée !</span>
            </div>
        @endif
    </div>
</div>
