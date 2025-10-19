<x-app-layout>
    <!-- Formation Details -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $formation->title }}
                    </h2>
                    <p class="text-gray-600 mb-6">
                        {{ $formation->description }}
                    </p>

                    <!-- Create Chapter Button -->
                    <div class="mb-6">
                        <form
                            action="{{
                                route(
                                    'formateur.formation.chapter.add.post',
                                    $formation
                                )
                            }}"
                            method="POST"
                        >
                            @csrf
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                            >
                                + Créer un chapitre
                            </button>
                        </form>
                    </div>

                    @if($formation->chapters->isEmpty())
                    <p class="text-gray-500">
                        Aucun chapitre trouvé pour cette formation.
                    </p>
                    @else
                    <div class="space-y-6">
                        @foreach($formation->chapters as $chapter)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xl font-semibold text-gray-800">
                                    Chapitre {{ $chapter->position }}:
                                    {{ $chapter->title }}
                                </h3>
                                <div>
                                    <a
                                        href="{{
                                            route(
                                                'formateur.formation.chapter.edit',
                                                [$formation, $chapter]
                                            )
                                        }}"
                                        >Modifier</a
                                    >
                                    <button
                                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1 px-3 rounded transition duration-200"
                                    >
                                        + Ajouter une leçon
                                    </button>
                                </div>
                            </div>

                            @if($chapter->lessons->isEmpty())
                            <p class="text-gray-500 text-sm">
                                Aucune leçon dans ce chapitre.
                            </p>
                            @else
                            <div class="space-y-2">
                                @foreach($chapter->lessons as $lesson)
                                <div
                                    class="flex items-center justify-between bg-gray-50 p-3 rounded"
                                >
                                    <div>
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                        >
                                            Leçon {{ $lesson->position }}:
                                            {{ $lesson->title }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-800 text-sm"
                                        >
                                            Modifier
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
