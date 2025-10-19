<div class="space-y-6">
    <!-- Formation Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center"
                    >
                        <span class="material-symbols-outlined text-white"
                            >school</span
                        >
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
                    <span class="material-symbols-outlined text-sm mr-2"
                        >edit</span
                    >
                    Modifier
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200"
                >
                    <span class="material-symbols-outlined text-sm mr-2"
                        >settings</span
                    >
                    Paramètres
                </button>
            </div>
        </div>
    </div>

    <!-- Chapters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center"
                    >
                        <span
                            class="material-symbols-outlined text-white text-sm"
                            >menu_book</span
                        >
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Chapitres
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $formation->chapters->count() }}
                            chapitre{{ $formation->chapters->count() > 1 ? 's' : '' }}
                        </p>
                    </div>
                </div>
                <button
                    wire:click="addChapter"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200"
                >
                    <span class="material-symbols-outlined text-sm mr-2"
                        >add</span
                    >
                    Ajouter un chapitre
                </button>
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($formation->chapters as $index => $chapter)
            <div
                class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold"
                        >
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">
                                @if($chapterEdition == $chapter->id)
                                <input
                                    type="text"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    wire:model="chapter.title"
                                />
                                @else

                                {{ $chapter->title }}

                                @endif
                            </h4>
                            <div class="flex items-center space-x-4 mt-1">
                                <span class="text-sm text-gray-500">
                                    {{ $chapter->lessons->count() }}
                                    leçon{{ $chapter->lessons->count() > 1 ? 's' : '' }}
                                </span>
                                @if($chapter->lessons->where('type',
                                'quiz')->count() > 0)
                                <span
                                    class="inline-flex items-center text-sm text-purple-600"
                                >
                                    <span
                                        class="material-symbols-outlined text-sm mr-1"
                                        >quiz</span
                                    >
                                    {{ $chapter->lessons->where('type', 'quiz')->count() }}
                                    quiz
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            wire:click="editChapter('{{ $chapter->id }}')"
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200"
                            title="Modifier le chapitre"
                        >
                            {{ $chapterEdition == $chapter->id ? 'Édition...' : '' }}
                            <span class="material-symbols-outlined text-sm"
                                >edit</span
                            >
                        </button>
                        <button
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                            title="Supprimer le chapitre"
                        >
                            <span class="material-symbols-outlined text-sm"
                                >delete</span
                            >
                        </button>
                        <button
                            class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200"
                            title="Réorganiser"
                        >
                            <span class="material-symbols-outlined text-sm"
                                >drag_handle</span
                            >
                        </button>
                    </div>
                </div>

                <!-- Lessons Preview -->
                @if($chapter->lessons->count() > 0)
                <div class="mt-4 ml-12">
                    <div class="flex flex-wrap gap-2">
                        @foreach($chapter->lessons->take(3) as $lesson)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700"
                        >
                            <span
                                class="material-symbols-outlined text-xs mr-1"
                            >
                                {{ $lesson->type === 'video' ? 'play_circle' : ($lesson->type === 'quiz' ? 'quiz' : 'article') }}
                            </span>
                            {{ Str::limit($lesson->title, 20) }}
                        </span>
                        @endforeach @if($chapter->lessons->count() > 3)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500"
                        >
                            +{{ $chapter->lessons->count() - 3 }} autres
                        </span>
                        @endif
                    </div>
                </div>
                @else
                <div class="mt-4 ml-12">
                    <span
                        class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-amber-50 text-amber-700 border border-amber-200"
                    >
                        <span class="material-symbols-outlined text-sm mr-2"
                            >warning</span
                        >
                        Aucun contenu dans ce chapitre
                    </span>
                </div>
                @endif
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div
                    class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <span
                        class="material-symbols-outlined text-gray-400 text-2xl"
                        >menu_book</span
                    >
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">
                    Aucun chapitre
                </h4>
                <p class="text-gray-500 mb-4">
                    Cette formation n'a pas encore de chapitres. Ajoutez le
                    premier chapitre pour commencer.
                </p>
                <button
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200"
                >
                    <span class="material-symbols-outlined text-sm mr-2"
                        >add</span
                    >
                    Créer le premier chapitre
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-blue-600"
                        >groups</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants inscrits
                    </p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $formation->learners->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-green-600"
                        >trending_up</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Taux de complétion
                    </p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $formation->learners->where('pivot.completed_at', '!=', null)->count() > 0 ? round(($formation->learners->where('pivot.completed_at', '!=', null)->count() / $formation->learners->count()) * 100) : 0































                        }}%
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-purple-600"
                        >star</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Note moyenne
                    </p>
                    <p class="text-2xl font-bold text-gray-900">4.8</p>
                </div>
            </div>
        </div>
    </div>
</div>
