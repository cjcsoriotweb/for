<x-app-layout>
    <!-- Formation Details -->
    @if($errors->any())
    <div class="mb-8">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5 text-red-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Erreur de validation
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div
                class="bg-gradient-to-r from-blue-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-2xl mb-8"
            >
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg
                                    class="h-8 w-8"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">
                                    Édition du Chapitre
                                </h1>
                                <p class="text-blue-100 mt-1">
                                    Modifiez les informations de votre chapitre
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold">
                                {{ $chapter->position }}
                            </div>
                            <div class="text-blue-100">Position</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-2xl"
                    >
                        <div class="px-8 py-6 border-b border-gray-200">
                            <h2
                                class="text-xl font-semibold text-gray-900 flex items-center"
                            >
                                <svg
                                    class="h-5 w-5 mr-2 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                                Informations du chapitre
                            </h2>
                        </div>

                        <form
                            action="{{
                                route(
                                    'formateur.formation.chapter.update',
                                    [$formation, $chapter]
                                )
                            }}"
                            method="POST"
                            class="p-8 space-y-6"
                        >
                            @csrf @method('PUT')

                            <div>
                                <label
                                    for="title"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Titre du chapitre
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="title"
                                        id="title"
                                        value="{{ old('title', $chapter->title) }}"
                                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white"
                                        placeholder="Entrez le titre du chapitre..."
                                        required
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    for="position"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    Position dans la formation
                                </label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                    >
                                        <svg
                                            class="h-5 w-5 text-gray-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        type="number"
                                        name="position"
                                        id="position"
                                        value="{{ old('position', $chapter->position) }}"
                                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white"
                                        placeholder="Numéro de position..."
                                        min="1"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Cette valeur détermine l'ordre d'affichage
                                    du chapitre dans la formation
                                </p>
                            </div>

                            <div
                                class="flex items-center justify-between pt-6 border-t border-gray-200"
                            >
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.show',
                                            $formation
                                        )
                                    }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                >
                                    <svg
                                        class="h-4 w-4 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                        />
                                    </svg>
                                    Retour à la formation
                                </a>

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <svg
                                        class="h-5 w-5 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-2xl"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3
                                class="text-lg font-semibold text-gray-900 flex items-center"
                            >
                                <svg
                                    class="h-5 w-5 mr-2 text-gray-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Informations
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <svg
                                            class="h-4 w-4 text-blue-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Titre actuel
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $chapter->title }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <svg
                                            class="h-4 w-4 text-green-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Position actuelle
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $chapter->position }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <svg
                                            class="h-4 w-4 text-purple-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V7"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            Formation
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $formation->title }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Danger Zone -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4
                                    class="text-sm font-semibold text-red-600 mb-4"
                                >
                                    Zone de danger
                                </h4>

                                @php $hasLessons = $chapter->lessons()->count()
                                > 0; @endphp @if($hasLessons)
                                <div
                                    class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg mb-4"
                                >
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-yellow-400"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Ce chapitre contient
                                                {{ $chapter->lessons()->count() }}
                                                leçon(s). Vous devez d'abord
                                                supprimer toutes les leçons
                                                avant de pouvoir supprimer ce
                                                chapitre.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    disabled
                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-gray-400 text-white font-medium rounded-xl cursor-not-allowed opacity-50"
                                >
                                    <svg
                                        class="h-4 w-4 mr-2"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        />
                                    </svg>
                                    Supprimer le chapitre (leçons présentes)
                                </button>
                                @else
                                <form
                                    action="{{
                                        route(
                                            'formateur.formation.chapter.delete.post',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    method="post"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chapitre ? Cette action est irréversible.')"
                                >
                                    @method('POST') @csrf

                                    <input
                                        type="hidden"
                                        name="chapter_id"
                                        value="{{ $chapter->id }}"
                                    />

                                    <button
                                        type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        <svg
                                            class="h-4 w-4 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                        Supprimer le chapitre
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
