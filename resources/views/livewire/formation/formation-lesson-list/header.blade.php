<div class="px-6 py-4 border-b border-gray-200 bg-white ml-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm"
            >
                <span class="material-symbols-outlined text-white text-lg"
                    >menu_book</span
                >
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-900">
                    Leçons du chapitre
                </h3>
                <p class="text-sm text-gray-500">
                    {{ $lessons->count() }}
                    leçon{{ $lessons->count() > 1 ? 's' : '' }}
                </p>
            </div>
        </div>

        <button
            wire:click="addLesson"
            class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-all duration-200 shadow-sm hover:shadow-md font-medium"
        >
            <span class="material-symbols-outlined text-lg mr-2">add</span>
            Ajouter une leçon
        </button>
    </div>
</div>
