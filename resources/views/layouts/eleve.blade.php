<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $team->name }} - Accueil</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: "#3B82F6",
                            secondary: "#10B981",
                            accent: "#F59E0B",
                        },
                    },
                },
            };
        </script>
    </head>
    <body class="bg-gray-50 font-sans">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-5 h-5 text-white"
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
                        </div>
                        <span
                            class="text-xl font-bold text-gray-900"
                            >{{ $team->name }}</span
                        >
                    </div>

                    <div class="hidden md:flex items-center space-x-8">
                        <a
                            href="#"
                            class="text-gray-700 hover:text-primary transition-colors"
                            >Accueil</a
                        >
                        <a
                            href="#"
                            class="text-gray-700 hover:text-primary transition-colors"
                            >Formations</a
                        >
                        <a
                            href="#"
                            class="text-gray-700 hover:text-primary transition-colors"
                            >Mon Espace</a
                        >
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Auth::user()->name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="md:hidden p-2">
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
                                d="M4 6h16M4 12h16M4 18h16"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{ $slot }}
        <!-- Welcome Section -->

        <!-- Formations en Cours -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Continuer mes formations
                    </h2>
                    <a
                        href="#"
                        class="text-primary hover:text-blue-600 font-medium"
                    >
                        Voir tout →
                    </a>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Formation React -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
                            >
                                En cours
                            </span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">
                            React.js Avancé
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Maîtrisez les concepts avancés de React
                        </p>
                        <div class="mb-4">
                            <div
                                class="flex justify-between text-sm text-gray-600 mb-1"
                            >
                                <span>Progression</span>
                                <span>75%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-primary h-2 rounded-full"
                                    style="width: 75%"
                                ></div>
                            </div>
                        </div>
                        <button
                            class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors"
                        >
                            Continuer
                        </button>
                    </div>

                    <!-- Formation Laravel -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                            >
                                En cours
                            </span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Laravel 11</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Framework PHP moderne
                        </p>
                        <div class="mb-4">
                            <div
                                class="flex justify-between text-sm text-gray-600 mb-1"
                            >
                                <span>Progression</span>
                                <span>45%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-primary h-2 rounded-full"
                                    style="width: 45%"
                                ></div>
                            </div>
                        </div>
                        <button
                            class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors"
                        >
                            Continuer
                        </button>
                    </div>

                    <!-- Formation Design -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a4 4 0 004-4V5z"
                                    ></path>
                                </svg>
                            </div>
                            <span
                                class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full"
                            >
                                En cours
                            </span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">UI/UX Design</h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Principes du design moderne
                        </p>
                        <div class="mb-4">
                            <div
                                class="flex justify-between text-sm text-gray-600 mb-1"
                            >
                                <span>Progression</span>
                                <span>30%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-primary h-2 rounded-full"
                                    style="width: 30%"
                                ></div>
                            </div>
                        </div>
                        <button
                            class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors"
                        >
                            Continuer
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formations Recommandées -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Formations recommandées pour vous
                    </h2>
                    <a
                        href="#"
                        class="text-primary hover:text-blue-600 font-medium"
                    >
                        Explorer tout →
                    </a>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Formation Node.js -->
                    <div
                        class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-200"
                    >
                        <div
                            class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-green-600"
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
                        </div>
                        <h3 class="font-semibold text-lg mb-2">
                            Node.js Backend
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Développez des APIs robustes
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-3 h-3 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        ></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600"
                                    >4.8 (120 avis)</span
                                >
                            </div>
                            <span class="text-sm font-semibold text-primary"
                                >Gratuit</span
                            >
                        </div>
                        <button
                            class="w-full border border-primary text-primary py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors"
                        >
                            S'inscrire
                        </button>
                    </div>

                    <!-- Formation DevOps -->
                    <div
                        class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-200"
                    >
                        <div
                            class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-orange-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">
                            DevOps Essentials
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Déploiement et automatisation
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-3 h-3 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        ></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600"
                                    >4.9 (85 avis)</span
                                >
                            </div>
                            <span class="text-sm font-semibold text-primary"
                                >Premium</span
                            >
                        </div>
                        <button
                            class="w-full border border-primary text-primary py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors"
                        >
                            Découvrir
                        </button>
                    </div>

                    <!-- Formation Marketing -->
                    <div
                        class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-200"
                    >
                        <div
                            class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-pink-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.5-1.985 7.5-4.743"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">
                            Marketing Digital
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            Stratégies et outils modernes
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-3 h-3 text-yellow-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        ></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600"
                                    >4.7 (203 avis)</span
                                >
                            </div>
                            <span class="text-sm font-semibold text-primary"
                                >Gratuit</span
                            >
                        </div>
                        <button
                            class="w-full border border-primary text-primary py-2 px-4 rounded-lg hover:bg-primary hover:text-white transition-colors"
                        >
                            S'inscrire
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2
                    class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center"
                >
                    Accès rapide
                </h2>

                <div class="grid md:grid-cols-4 gap-6">
                    <a
                        href="#"
                        class="bg-gray-50 hover:bg-gray-100 p-6 rounded-xl text-center transition-colors"
                    >
                        <div
                            class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-primary"
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
                        </div>
                        <h3 class="font-semibold mb-2">Mes certificats</h3>
                        <p class="text-sm text-gray-600">
                            Télécharger mes attestations
                        </p>
                    </a>

                    <a
                        href="#"
                        class="bg-gray-50 hover:bg-gray-100 p-6 rounded-xl text-center transition-colors"
                    >
                        <div
                            class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-secondary"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Support</h3>
                        <p class="text-sm text-gray-600">Obtenir de l'aide</p>
                    </a>

                    <a
                        href="#"
                        class="bg-gray-50 hover:bg-gray-100 p-6 rounded-xl text-center transition-colors"
                    >
                        <div
                            class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-accent"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Communauté</h3>
                        <p class="text-sm text-gray-600">
                            Échanger avec les autres
                        </p>
                    </a>

                    <a
                        href="#"
                        class="bg-gray-50 hover:bg-gray-100 p-6 rounded-xl text-center transition-colors"
                    >
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-6 h-6 text-purple-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                ></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Statistiques</h3>
                        <p class="text-sm text-gray-600">
                            Suivre ma progression
                        </p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div
                                class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
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
                            </div>
                            <span class="text-xl font-bold">FormaPro</span>
                        </div>
                        <p class="text-gray-400 max-w-md">
                            Plateforme d'apprentissage moderne dédiée à votre
                            réussite professionnelle.
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-4">Formations</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Développement</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Design</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Marketing</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Business</a
                                >
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Aide</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Contact</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >FAQ</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400"
                >
                    <p>&copy; 2024 FormaPro. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
