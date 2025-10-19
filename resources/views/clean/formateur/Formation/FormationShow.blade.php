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
                                    <form
                                        method="post"
                                        action="{{
                                            route(
                                                'formateur.formation.chapter.lesson.add.post',
                                                [$formation, $chapter]
                                            )
                                        }}"
                                    >
                                        @csrf

                                        <button
                                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1 px-3 rounded transition duration-200"
                                        >
                                            + Ajouter une leçon
                                        </button>
                                    </form>
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
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                        >
                                            Leçon {{ $lesson->position }}:
                                            {{ $lesson->title }}
                                        </span>

                                        @if($lesson->lessonable)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium @if($lesson->lessonable_type === 'App\Models\Quiz') bg-blue-100 text-blue-800 @elseif($lesson->lessonable_type === 'App\Models\VideoContent') bg-green-100 text-green-800 @elseif($lesson->lessonable_type === 'App\Models\TextContent') bg-purple-100 text-purple-800 @else bg-gray-100 text-gray-800 @endif"
                                        >
                                            @if($lesson->lessonable_type ===
                                            'App\Models\Quiz') Quiz
                                            @elseif($lesson->lessonable_type ===
                                            'App\Models\VideoContent') Vidéo
                                            @elseif($lesson->lessonable_type ===
                                            'App\Models\TextContent') Texte
                                            @else
                                            {{ class_basename($lesson->lessonable_type) }}
                                            @endif
                                        </span>
                                        @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                        >
                                            Non défini
                                        </span>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($lesson->lessonable)
                                        @if($lesson->lessonable_type ===
                                        'App\Models\Quiz')
                                        <a
                                            href="{{
                                                route(
                                                    'formateur.formation.chapter.lesson.quiz.edit',
                                                    [
                                                        $formation,
                                                        $chapter,
                                                        $lesson
                                                    ]
                                                )
                                            }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            Modifier Quiz
                                        </a>
                                        @elseif($lesson->lessonable_type ===
                                        'App\Models\VideoContent')
                                        <a
                                            href="{{
                                                route(
                                                    'formateur.formation.chapter.lesson.video.edit',
                                                    [
                                                        $formation,
                                                        $chapter,
                                                        $lesson
                                                    ]
                                                )
                                            }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            Modifier Vidéo
                                        </a>
                                        @elseif($lesson->lessonable_type ===
                                        'App\Models\TextContent')
                                        <a
                                            href="{{
                                                route(
                                                    'formateur.formation.chapter.lesson.text.edit',
                                                    [
                                                        $formation,
                                                        $chapter,
                                                        $lesson
                                                    ]
                                                )
                                            }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            Modifier Texte
                                        </a>
                                        @endif @else
                                        <a
                                            href="{{
                                                route(
                                                    'formateur.formation.chapter.lesson.define',
                                                    [
                                                        $formation,
                                                        $chapter,
                                                        $lesson
                                                    ]
                                                )
                                            }}"
                                            class="text-green-600 hover:text-green-800 text-sm font-medium"
                                        >
                                            Définir le type
                                        </a>
                                        @endif
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
