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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        {{ __("Quelle genre de leçon voulez-vous créer?") }}
                    </h2>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'formation.chapter.lesson.define.store',
                                [$formation, $chapter, $lesson]
                            )
                        }}"
                        class="space-y-6"
                    >
                        @csrf

                        <div class="space-y-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-3"
                            >
                                Choisissez le type de leçon :
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Quiz Option -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="lesson_type"
                                        value="quiz"
                                        class="sr-only peer"
                                        required
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
                                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                    ></path>
                                                </svg>
                                                Quiz
                                            </span>
                                            <span
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                Questions à choix multiples avec
                                                système de notation
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
                                    <!--- bg -->
                                    <div
                                        class="absolute inset-0 rounded-lg bg-indigo-50 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    ></div>
                                </label>

                                <!-- Video Option -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="lesson_type"
                                        value="video"
                                        class="sr-only peer"
                                        required
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
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                    ></path>
                                                </svg>
                                                Vidéo
                                            </span>
                                            <span
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                Contenu vidéo avec suivi du
                                                temps de visionnage
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
                                    <!--- bg -->
                                    <div
                                        class="absolute inset-0 rounded-lg bg-indigo-50 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    ></div>
                                </label>

                                <!-- Text Option -->
                                <label
                                    class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-gray-300 hover:border-indigo-500 transition-colors"
                                >
                                    <input
                                        type="radio"
                                        name="lesson_type"
                                        value="text"
                                        class="sr-only peer"
                                        required
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
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    ></path>
                                                </svg>
                                                Texte
                                            </span>
                                            <span
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                Contenu textuel avec suivi de
                                                lecture
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
                                    <!--- bg -->
                                    <div
                                        class="absolute inset-0 rounded-lg bg-indigo-50 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end pt-6 border-t border-gray-200"
                        >
                            <button
                                type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Continuer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
