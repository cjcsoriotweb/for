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
                                        'formateur.formation.edit',
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
                                    >Ajouter une Vidéo</span
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
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                Ajouter une Vidéo
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Téléchargez ou intégrez une vidéo pour votre
                                leçon
                            </p>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formateur.formation.chapter.lesson.video.store',
                                [$formation, $chapter, $lesson]
                            )
                        }}"
                        enctype="multipart/form-data"
                        class="space-y-6"
                    >
                        @csrf

                        <!-- Video Title -->
                        <div>
                            <label
                                for="video_title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Titre de la Vidéo *
                            </label>
                            <input
                                type="text"
                                id="video_title"
                                name="video_title"
                                value="{{ old('video_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_title') border-red-500 @enderror"
                                placeholder="Ex: Introduction aux concepts de base"
                                required
                            />
                            @error('video_title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Video Description -->
                        <div>
                            <label
                                for="video_description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Description de la Vidéo
                            </label>
                            <textarea
                                id="video_description"
                                name="video_description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_description') border-red-500 @enderror"
                                placeholder="Décrivez brièvement le contenu de cette vidéo..."
                                >{{ old("video_description") }}</textarea
                            >
                            @error('video_description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Video Source Options -->
                        <div class="space-y-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Source de la Vidéo *
                            </label>

                            <div class="space-y-3">
                                <!-- File Upload Option -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="video_source"
                                        value="upload"
                                        class="sr-only peer"
                                        checked
                                    />
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span
                                                class="flex items-center text-sm font-medium text-gray-900"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-2 text-indigo-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                    ></path>
                                                </svg>
                                                Télécharger un fichier vidéo
                                            </span>
                                            <span
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                Formats supportés: MP4, AVI,
                                                MOV, WebM (max 500MB)
                                            </span>
                                        </span>
                                    </span>
                                    <div class="ml-3 flex h-5 items-center">
                                        <div
                                            class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 flex items-center justify-center"
                                        >
                                            <div
                                                class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute inset-0 rounded-lg bg-indigo-50 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    ></div>
                                </label>

                                <!-- Video URL Option -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="video_source"
                                        value="url"
                                        class="sr-only peer"
                                    />
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span
                                                class="flex items-center text-sm font-medium text-gray-900"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-2 text-indigo-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                                    ></path>
                                                </svg>
                                                Intégrer depuis une URL
                                            </span>
                                            <span
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                URL externe (hébergement vidéo)
                                            </span>
                                        </span>
                                    </span>
                                    <div class="ml-3 flex h-5 items-center">
                                        <div
                                            class="h-5 w-5 rounded-full border-2 border-gray-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-600 flex items-center justify-center"
                                        >
                                            <div
                                                class="h-2 w-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"
                                            ></div>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute inset-0 rounded-lg bg-indigo-50 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <!-- Video Preview Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">
                                Aperçu de la Vidéo
                            </h3>
                            <div id="video-preview-container" class="space-y-3">
                                <div
                                    class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center"
                                >
                                    <div class="text-center text-gray-500">
                                        <svg
                                            class="w-12 h-12 mx-auto mb-2"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <p class="text-sm">
                                            Aucun aperçu disponible
                                        </p>
                                        <p class="text-xs mt-1">
                                            Sélectionnez une source vidéo pour
                                            voir l'aperçu
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Area (shown when upload is selected) -->
                        <div id="upload-area" class="space-y-4">
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-6"
                            >
                                <div class="text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 48 48"
                                    >
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                    <div class="mt-4">
                                        <label
                                            for="video_file"
                                            class="cursor-pointer"
                                        >
                                            <span
                                                class="mt-2 block text-sm font-medium text-gray-900"
                                                >Sélectionner un fichier
                                                vidéo</span
                                            >
                                            <span
                                                class="mt-1 block text-sm text-gray-500"
                                                >MP4, AVI, MOV jusqu'à
                                                500MB</span
                                            >
                                            <input
                                                id="video_file"
                                                name="video_file"
                                                type="file"
                                                class="sr-only"
                                                accept="video/*"
                                            />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Video URL Input (hidden by default) -->
                        <div id="url-area" class="hidden space-y-4">
                            <div>
                                <label
                                    for="video_url"
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    URL de la Vidéo *
                                </label>
                                <input
                                    type="url"
                                    id="video_url"
                                    name="video_url"
                                    value="{{ old('video_url') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_url') border-red-500 @enderror"
                                    placeholder="https://exemple.com/video.mp4"
                                />
                                @error('video_url')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Video Duration -->
                        <div>
                            <label
                                for="video_duration"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Durée de la Vidéo (minutes)
                            </label>
                            <input
                                type="number"
                                id="video_duration"
                                name="video_duration"
                                value="{{ old('video_duration') }}"
                                min="1"
                                max="300"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_duration') border-red-500 @enderror"
                                placeholder="Ex: 45"
                            />
                            @error('video_duration')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                            @enderror
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
                                    Ajouter la Vidéo
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between upload and URL input
        document
            .querySelectorAll('input[name="video_source"]')
            .forEach((radio) => {
                radio.addEventListener("change", function () {
                    const uploadArea = document.getElementById("upload-area");
                    const urlArea = document.getElementById("url-area");

                    if (this.value === "upload") {
                        uploadArea.classList.remove("hidden");
                        urlArea.classList.add("hidden");
                    } else {
                        uploadArea.classList.add("hidden");
                        urlArea.classList.remove("hidden");
                    }
                });
            });

        // Video preview functionality
        document
            .getElementById("video_url")
            .addEventListener("input", function () {
                const videoUrl = this.value;
                const previewContainer = document.getElementById(
                    "video-preview-container"
                );

                if (videoUrl.trim() === "") {
                    // Show default preview when URL is empty
                    previewContainer.innerHTML = `
                    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm">Aucun aperçu disponible</p>
                            <p class="text-xs mt-1">Sélectionnez une source vidéo pour voir l'aperçu</p>
                        </div>
                    </div>
                `;
                    return;
                }

                // Extract video ID and platform
                let videoId = null;
                let platform = null;

                // Generic video URL handling - just show a placeholder
                previewContainer.innerHTML = `
                <div class="aspect-video bg-gray-800 rounded-lg flex items-center justify-center text-white">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm">Aperçu disponible lors de la lecture</p>
                    </div>
                </div>
            `;
            });
    </script>
</x-app-layout>
