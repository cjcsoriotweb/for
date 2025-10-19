<div>
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-white text-sm"
                        >menu_book</span
                    >
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Leçons</h3>
                    <p class="text-sm text-gray-500"></p>
                </div>
            </div>

            <button
                wire:click="addLesson"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200"
            >
                <span class="material-symbols-outlined text-sm mr-2">add</span>
                Ajouter une leçon
            </button>
        </div>
    </div>

    <div class="divide-y divide-gray-200">
        @forelse($lessons as $lesson)
        <div
            class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
            wire:key="lesson-row-{{ $lesson->id }}"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 w-full">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">
                            <div wire:key="lessonsById-{{ $lesson->id }}">
                                @if($lessonEdition === $lesson->id)
                                <input
                                    type="text"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:model.defer="lessonsById.{{ $lesson->id }}"
                                />
                                @error("lessonsById.$lesson->id")
                                <p class="text-sm text-red-600 mt-1">
                                    {{ $message }}
                                </p>
                                @enderror

                                <div class="mt-2 flex gap-2">
                                    <button
                                        wire:click="saveLesson({{ $lesson->id }})"
                                        class="px-3 py-1 rounded-lg bg-blue-600 text-white"
                                    >
                                        Enregistrer
                                    </button>
                                    <button
                                        wire:click="cancelEdit"
                                        class="px-3 py-1 rounded-lg bg-gray-100"
                                    >
                                        Annuler
                                    </button>
                                </div>
                                @else
                                {{ $lessonsById[$lesson->id] ?? $lesson->title }}
                                @endif
                            </div>
                        </h4>
                        {{-- … autres infos/badges sur la leçon si besoin --}}
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <button
                        wire:click="editLesson({{ $lesson->id }})"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                        title="Modifier la leçon"
                    >
                        <span class="material-symbols-outlined text-sm"
                            >edit</span
                        >
                    </button>
                    {{-- autres boutons d’actions éventuels --}}
                </div>
            </div>

            {{-- preview sous-éléments de la leçon si nécessaire --}}
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-500">
            Aucune leçon pour le moment.
            <button
                wire:click="addLesson"
                class="ml-2 inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg"
            >
                <span class="material-symbols-outlined text-sm mr-1">add</span>
                Créer la première leçon
            </button>
        </div>
        @endforelse
    </div>
</div>
