<x-app-layout>
    <!-- Formation Details -->
    @if($errors->any())
    <div
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
        role="alert"
    >
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline"
            >Veuillez corriger les erreurs suivantes:</span
        >
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a
                                href="{{
                                    route(
                                        'formateur.formation.show',
                                        $formation
                                    )
                                }}"
                                class="text-sm text-gray-700 hover:text-indigo-600"
                            >
                                {{ $formation->title }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.edit',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    class="text-sm text-gray-700 hover:text-indigo-600 ml-1"
                                >
                                    Chapitre {{ $chapter->position }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1"
                                    >Ajouter du Contenu Textuel</span
                                >
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg
                                class="w-6 h-6 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                Ajouter du Contenu Textuel
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Rédigez du contenu pédagogique pour vos
                                apprenants
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.formation.chapter.lesson.text.store',
                                [$formation, $chapter, $lesson]
                            )
                        }}"
                        class="space-y-6"
                    >
                        @csrf

                        <!-- Content Title -->
                        <div>
                            <label
                                for="content_title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Titre du Contenu *
                            </label>
                            <input
                                type="text"
                                id="content_title"
                                name="content_title"
                                value="{{ old('content_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('content_title') border-red-500 @enderror"
                                placeholder="Ex: Introduction aux concepts fondamentaux"
                                required
                            />
                            @error('content_title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Content Description -->
                        <div>
                            <label
                                for="content_description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Résumé du Contenu
                            </label>
                            <textarea
                                id="content_description"
                                name="content_description"
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('content_description') border-red-500 @enderror"
                                placeholder="Brève description de ce que les apprenants vont apprendre..."
                                >{{ old("content_description") }}</textarea
                            >
                            @error('content_description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Main Content -->
                        <div>
                            <label
                                for="content_text"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Contenu de la Leçon *
                            </label>
                            <textarea
                                id="content_text"
                                name="content_text"
                                rows="20"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('content_text') border-red-500 @enderror"
                                placeholder="Rédigez votre contenu pédagogique ici... Vous pouvez utiliser le format Markdown pour la mise en forme."
                                required
                                >{{ old("content_text") }}</textarea
                            >
                            @error('content_text')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                💡 Astuce: Vous pouvez utiliser le format
                                Markdown pour ajouter des titres, listes, liens,
                                etc.
                            </p>
                        </div>

                        <!-- Content Settings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    for="estimated_read_time"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Temps de lecture estimé (minutes)
                                </label>
                                <input
                                    type="number"
                                    id="estimated_read_time"
                                    name="estimated_read_time"
                                    value="{{ old('estimated_read_time', 10) }}"
                                    min="1"
                                    max="120"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('estimated_read_time') border-red-500 @enderror"
                                />
                                @error('estimated_read_time')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Options de lecture
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                        name="allow_download" value="1"
                                        {{
                                            old("allow_download")
                                                ? "checked"
                                                : ""
                                        }}
                                        class="rounded border-gray-300
                                        text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-700"
                                        >
                                            Permettre le téléchargement PDF
                                        </span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                        name="show_progress" value="1"
                                        {{
                                            old("show_progress", true)
                                                ? "checked"
                                                : ""
                                        }}
                                        class="rounded border-gray-300
                                        text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span
                                            class="ml-2 text-sm text-gray-700"
                                        >
                                            Afficher la barre de progression
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex items-center justify-between pt-6 border-t border-gray-200"
                        >
                            <a
                                href="{{
                                    route(
                                        'formateur.formation.chapter.lesson.define',
                                        [$formation, $chapter, $lesson]
                                    )
                                }}"
                                class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                            >
                                ← Retour au choix du type
                            </a>
                            <div class="space-x-3">
                                <button
                                    type="button"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Enregistrer comme brouillon
                                </button>
                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Créer le Contenu
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
