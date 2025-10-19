<div class="flex items-start justify-between">
    <div class="flex-1">
        <div class="flex items-center space-x-3 mb-4">
            <div
                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center"
            >
                <span class="material-symbols-outlined text-white">school</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $formation->title }}
                </h1>
                <div class="flex items-center space-x-2 mt-1">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                    >
                        {{ ucfirst($formation->level ?? 'débutant') }}
                    </span>
                    @if($formation->money_amount)
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                    >
                        {{ number_format($formation->money_amount, 0, ',', ' ') }}
                        €
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <p class="text-gray-600 leading-relaxed">
            {{ $formation->description }}
        </p>
    </div>
    <div class="flex items-center space-x-2 ml-6">
        <button
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
        >
            <span class="material-symbols-outlined text-sm mr-2">edit</span>
            Modifier
        </button>
        <button
            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
        >
            <span class="material-symbols-outlined text-sm mr-2">settings</span>
            Paramètres
        </button>
    </div>
</div>
