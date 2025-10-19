<div class="w-full">
    <!-- Header Section -->
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

    <!-- Lessons List -->
    <div class="bg-white">
        @forelse($lessons as $lesson)
        <div
            class="group px-6 py-6 hover:bg-gray-50 transition-all duration-200 border-b border-gray-100 ml-6"
            wire:key="lesson-row-{{ $lesson->id }}"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6 flex-1 min-w-0">
                    <!-- Lesson Icon & Number -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm"
                        >
                            <span
                                class="material-symbols-outlined text-white text-lg"
                                >article</span
                            >
                        </div>
                    </div>

                    <!-- Lesson Content -->
                    <div class="flex-1 min-w-0">
                        <div wire:key="lessonsById-{{ $lesson->id }}">
                            @if($lessonEdition === $lesson->id)
                            <div class="space-y-3">
                                <input
                                    type="text"
                                    class="w-full text-lg font-medium border-2 border-blue-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    wire:model.defer="lessonsById.{{ $lesson->id }}"
                                    placeholder="Titre de la leçon..."
                                />
                                @error("lessonsById.$lesson->id")
                                <p
                                    class="text-sm text-red-600 flex items-center"
                                >
                                    <span
                                        class="material-symbols-outlined text-sm mr-1"
                                        >error</span
                                    >
                                    {{ $message }}
                                </p>
                                @enderror

                                <div class="flex gap-3">
                                    <button
                                        wire:click="saveLesson({{ $lesson->id }})"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition-colors duration-200"
                                    >
                                        <span
                                            class="material-symbols-outlined text-sm mr-2"
                                            >check</span
                                        >
                                        Enregistrer
                                    </button>
                                    <button
                                        wire:click="cancelEdit"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition-colors duration-200"
                                    >
                                        <span
                                            class="material-symbols-outlined text-sm mr-2"
                                            >close</span
                                        >
                                        Annuler
                                    </button>
                                </div>
                            </div>
                            @else
                            <h4
                                class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200"
                            >
                                {{ $lessonsById[$lesson->id] ?? $lesson->title }}
                            </h4>
                            <div
                                class="mt-1 flex items-center space-x-4 text-sm text-gray-500"
                            >
                                <span class="flex items-center">
                                    <span
                                        class="material-symbols-outlined text-sm mr-1"
                                        >tag</span
                                    >
                                    Position {{ $lesson->position }}
                                </span>
                                <span class="flex items-center">
                                    <span
                                        class="material-symbols-outlined text-sm mr-1"
                                        >event</span
                                    >
                                    Créée récemment
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @unless($lessonEdition === $lesson->id)
                <div
                    class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                >
                    <button
                        wire:click="editLesson({{ $lesson->id }})"
                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
                        title="Modifier la leçon"
                    >
                        <span class="material-symbols-outlined text-lg"
                            >edit</span
                        >
                    </button>
                    <button
                        wire:click="confirmDeleteLesson({{ $lesson->id }})"
                        wire:confirm="Êtes-vous sûr de vouloir supprimer cette leçon ? Cette action est irréversible."
                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                        title="Supprimer la leçon"
                    >
                        <span class="material-symbols-outlined text-lg"
                            >delete</span
                        >
                    </button>
                </div>
                @endunless
            </div>
        </div>
        @empty
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
        @endforelse
    </div>
</div>
