{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>403 — Accès interdit</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <x-meta-header />
        <style>
            @keyframes float {
                0%,
                100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            @keyframes pulse {
                0%,
                100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.7;
                }
            }
            .float-animation {
                animation: float 3s ease-in-out infinite;
            }
            .pulse-animation {
                animation: pulse 2s ease-in-out infinite;
            }
        </style>
    </head>
    <body
        class="min-h-screen bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50 dark:from-gray-900 dark:via-red-900/20 dark:to-orange-900/20 text-gray-900 dark:text-gray-100"
    >
        <div class="flex min-h-screen items-center justify-center p-6">
            <div class="max-w-2xl w-full">
                {{-- Hero Section avec animation --}}
                <div class="text-center mb-8">
                    <div class="float-animation mb-6">
                        <div
                            class="w-24 h-24 mx-auto bg-gradient-to-br from-red-500 to-orange-600 rounded-full flex items-center justify-center shadow-2xl"
                        >
                            <svg
                                class="w-12 h-12 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <h1
                        class="text-6xl font-bold mb-4 bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent"
                    >
                        403
                    </h1>

                    <h2
                        class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200"
                    >
                        Accès Interdit
                    </h2>

                    <div
                        class="w-16 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto mb-6 rounded-full"
                    ></div>
                </div>

                {{-- Card principale --}}
                <div
                    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-white/20 dark:border-gray-700/50 p-8 shadow-2xl"
                >
                    {{-- Message d'erreur avec icône --}}
                    <div class="text-center mb-8">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full mb-4 pulse-animation"
                        >
                            <svg
                                class="w-8 h-8 text-red-600 dark:text-red-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                ></path>
                            </svg>
                        </div>

                        <p
                            class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed"
                        >
                            @if(!empty($message))
                            {{ $message }}
                            @else Désolé, vous n'avez pas l'autorisation
                            d'accéder à cette ressource.
                            <br class="hidden sm:block" />
                            <span class="text-sm mt-2 inline-block"
                                >Cette page nécessite des permissions
                                spéciales.</span
                            >
                            @endif
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center"
                    >
                        @guest
                        {{-- Utilisateur non connecté - Priorité à la connexion --}}
                        <a
                            href="{{ route('login') }}"
                            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                ></path>
                            </svg>
                            Se connecter
                        </a>

                        <a
                            href="{{ url()->previous() }}"
                            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                ></path>
                            </svg>
                            Retour
                        </a>
                        @else
                        {{-- Utilisateur connecté mais non autorisé --}}
                        <a
                            href="{{ url()->previous() }}"
                            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                ></path>
                            </svg>
                            Retour
                        </a>

                        <a
                            href="{{ url('') }}"
                            class="group inline-flex items-center px-6 py-3 bg-white/50 dark:bg-gray-700/50 hover:bg-white/70 dark:hover:bg-gray-700/70 text-gray-700 dark:text-gray-200 font-medium rounded-xl border border-gray-200 dark:border-gray-600 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg
                                class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                            Accueil
                        </a>

                        <x-forms.auth.logout class="inline">
                            <button
                                type="submit"
                                class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                            >
                                <svg
                                    class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    ></path>
                                </svg>
                                Déconnexion
                            </button>
                        </x-forms.auth.logout>
                        @endguest
                    </div>

                    {{-- Informations supplémentaires --}}
                    <div
                        class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700"
                    >
                        <div
                            class="text-center text-sm text-gray-500 dark:text-gray-400"
                        >
                            <p>
                                Si vous pensez que c'est une erreur, contactez
                                votre administrateur.
                            </p>
                            <p class="mt-1">
                                Code d'erreur:
                                <span
                                    class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded"
                                    >HTTP_403</span
                                >
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Éléments décoratifs --}}
                <div
                    class="absolute top-10 left-10 w-20 h-20 bg-gradient-to-br from-red-400/20 to-orange-400/20 rounded-full blur-xl"
                ></div>
                <div
                    class="absolute bottom-10 right-10 w-32 h-32 bg-gradient-to-br from-orange-400/20 to-yellow-400/20 rounded-full blur-xl"
                ></div>
            </div>
        </div>
    </body>
</html>
