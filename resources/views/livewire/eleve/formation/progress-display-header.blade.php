<div wire:poll.2s>
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-5 rounded-xl border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between mb-3">
            <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">Progression de lecture</div>
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div class="text-3xl font-bold text-blue-900 dark:text-blue-100 mb-2">{{ $readPercent }}%</div>
        <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $readPercent }}%"></div>
        </div>
    </div>
</div>
