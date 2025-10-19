<div class="px-6 py-16 text-center ml-6">
    <div
        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
    >
        <span class="material-symbols-outlined text-gray-400 text-2xl"
            >menu_book</span
        >
    </div>
    <h4 class="text-lg font-medium text-gray-900 mb-2">
        Aucune leçon pour le moment
    </h4>
    <p class="text-gray-500 mb-6">
        Commencez par créer votre première leçon pour ce chapitre.
    </p>
    <button
        wire:click="addLesson"
        class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-all duration-200 shadow-sm hover:shadow-md font-medium"
    >
        <span class="material-symbols-outlined text-lg mr-2">add</span>
        Créer la première leçon
    </button>
</div>
