<x-app-layout>
    <!-- Formation Details -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Formation Header Card -->
            <div
                class="bg-gradient-to-br from-indigo-50 to-blue-50 overflow-hidden shadow-sm sm:rounded-xl border border-indigo-100 mb-8"
            >
                <div class="p-8">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1
                                class="text-3xl font-bold text-gray-900 mb-3 flex items-center"
                            >
                                <svg
                                    class="w-8 h-8 text-indigo-600 mr-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    ></path>
                                </svg>
                                {{ $formation->title }}
                            </h1>
                            <p class="text-gray-700 text-lg leading-relaxed">
                                {{ $formation->description }}
                            </p>
                        </div>
                        <div class="ml-6 flex items-center space-x-3">
                            <button
                                type="button"
                                onclick="openEditModal()"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-colors duration-200"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    ></path>
                                </svg>
                                Modifier
                            </button>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800"
                            >
                                Formation Active
                            </span>
                        </div>
                    </div>

                    <!-- Create Chapter Button -->
                    <div class="flex justify-end">
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
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            >
                                <svg
                                    class="w-5 h-5 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                                Créer un chapitre
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if($formation->chapters->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg
                    class="mx-auto h-24 w-24 text-gray-400 mb-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">
                    Aucun chapitre trouvé
                </h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">
                    Commencez par créer votre premier chapitre pour structurer
                    votre formation.
                </p>
            </div>
            @else
            <div class="grid gap-6">
                @foreach($formation->chapters as $chapter)
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden"
                >
                    <!-- Chapter Header -->
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-full"
                                >
                                    <span
                                        class="text-indigo-600 font-bold text-sm"
                                        >{{ $chapter->position }}</span
                                    >
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $chapter->title }}
                                </h3>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.edit',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200"
                                >
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        ></path>
                                    </svg>
                                    Modifier
                                </a>
                                <form
                                    method="post"
                                    action="{{
                                        route(
                                            'formateur.formation.chapter.lesson.add.post',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    class="inline"
                                >
                                    @csrf
                                    <button
                                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            ></path>
                                        </svg>
                                        Ajouter une leçon
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($chapter->lessons->isEmpty())
                    <!-- Empty Lessons State -->
                    <div class="px-6 py-8 text-center">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400 mb-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                        <p class="text-gray-500 text-sm">
                            Aucune leçon dans ce chapitre.
                        </p>
                        <p class="text-gray-400 text-xs mt-1">
                            Ajoutez votre première leçon pour commencer.
                        </p>
                    </div>
                    @else
                    <!-- Lessons List -->
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($chapter->lessons as $lesson)
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200"
                            >
                                <div class="flex items-center justify-between">
                                    <div
                                        class="flex items-center space-x-4 flex-1"
                                    >
                                        <!-- Lesson Position Badge -->
                                        <div
                                            class="flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-full"
                                        >
                                            <span
                                                class="text-xs font-bold text-gray-600"
                                                >{{ $lesson->position }}</span
                                            >
                                        </div>

                                        <!-- Lesson Title -->
                                        <div class="flex-1">
                                            <h4
                                                class="text-sm font-semibold text-gray-900 mb-1"
                                            >
                                                {{ $lesson->title }}
                                            </h4>
                                        </div>

                                        <!-- Lesson Type Badge -->
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            @if($lesson->lessonable)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{
                                                                    $lesson->lessonable_type === 'App\Models\Quiz' ? 'bg-blue-100 text-blue-800' :
                                                                    ($lesson->lessonable_type === 'App\Models\VideoContent' ? 'bg-green-100 text-green-800' :
                                                                    ($lesson->lessonable_type === 'App\Models\TextContent' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))
                                                                }}"
                                            >
                                                @if($lesson->lessonable_type ===
                                                'App\Models\Quiz')
                                                <svg
                                                    class="w-3 h-3 mr-1"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"
                                                    ></path>
                                                </svg>
                                                Quiz
                                                @elseif($lesson->lessonable_type
                                                === 'App\Models\VideoContent')
                                                <svg
                                                    class="w-3 h-3 mr-1"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                                                    ></path>
                                                </svg>
                                                Vidéo
                                                @elseif($lesson->lessonable_type
                                                === 'App\Models\TextContent')
                                                <svg
                                                    class="w-3 h-3 mr-1"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                        clip-rule="evenodd"
                                                    ></path>
                                                </svg>
                                                Texte @else
                                                {{ class_basename($lesson->lessonable_type) }}
                                                @endif
                                            </span>
                                            @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                            >
                                                <svg
                                                    class="w-3 h-3 mr-1"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"
                                                    ></path>
                                                </svg>
                                                Non défini
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Lesson Actions -->
                                    <div
                                        class="flex items-center space-x-2 ml-4"
                                    >
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
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                ></path>
                                            </svg>
                                            Modifier
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
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                ></path>
                                            </svg>
                                            Modifier
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
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                ></path>
                                            </svg>
                                            Modifier
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
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50 rounded-md transition-colors duration-200"
                                            title="Choisir le type de leçon (Quiz, Vidéo ou Texte)"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                ></path>
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                ></path>
                                            </svg>
                                            Définir le type
                                        </a>
                                        @endif

                                        <!-- Delete Lesson Button -->
                                        <form
                                            method="POST"
                                            action="{{
                                                route(
                                                    'formateur.formation.chapter.lesson.delete.post',
                                                    [
                                                        $formation,
                                                        $chapter,
                                                        $lesson
                                                    ]
                                                )
                                            }}"
                                            class="inline"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon? Cette action est irréversible.')"
                                        >
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors duration-200"
                                            >
                                                <svg
                                                    class="w-3 h-3 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    ></path>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Edit Formation Modal -->
    <div
        id="editModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-xl bg-white"
        >
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">
                        Modifier la formation
                    </h3>
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>

                <form
                    action="{{
                        route('formateur.formation.update', $formation)
                    }}"
                    method="POST"
                >
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Titre</label
                        >
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $formation->title) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Entrez le titre de la formation"
                        />
                        @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Entrez la description de la formation"
                            >{{ old('description', $formation->description) }}</textarea
                        >
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors duration-200"
                        >
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Display success message -->
    @if(session('success'))
    <div
        id="successMessage"
        class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50"
    >
        <div class="flex items-center">
            <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
            </svg>
            {{ session("success") }}
        </div>
    </div>
    @endif

    <script>
        function openEditModal() {
            document.getElementById("editModal").classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        }

        function closeEditModal() {
            document.getElementById("editModal").classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }

        // Close modal when clicking outside
        document
            .getElementById("editModal")
            .addEventListener("click", function (e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

        // Auto-hide success message after 5 seconds
        const successMessage = document.getElementById("successMessage");
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = "0";
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }, 5000);
        }
    </script>
</x-app-layout>
