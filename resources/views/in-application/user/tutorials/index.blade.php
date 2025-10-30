<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutoriels - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tutoriels vidéo</h1>
                <p class="text-gray-600">Découvrez notre plateforme à travers nos tutoriels</p>
            </div>

            <!-- Liste des tutoriels -->
            <div id="tutorial-list" class="space-y-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 cursor-pointer" onclick="openTutorial('introduction', 'Introduction à la plateforme', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Découvrez les bases de notre plateforme de formation')">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479l-6.16 3.422L5.839 17.057a12.083 12.083 0 01.665-6.479L12 14z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l0 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Introduction à la plateforme</h3>
                                    <p class="text-gray-600 mt-1">Découvrez les bases de notre plateforme de formation</p>
                                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                        <span>5 min</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Débutant</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 cursor-pointer" onclick="openTutorial('formations', 'Créer et gérer vos formations', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Apprenez à créer des formations et à les organiser')">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Créer et gérer vos formations</h3>
                                    <p class="text-gray-600 mt-1">Apprenez à créer des formations et à les organiser</p>
                                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                        <span>15 min</span>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Formateur</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 cursor-pointer" onclick="openTutorial('eleves', 'Gestion des élèves', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Suivez la progression de vos élèves et leurs résultats')">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Gestion des élèves</h3>
                                    <p class="text-gray-600 mt-1">Suivez la progression de vos élèves et leurs résultats</p>
                                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                        <span>10 min</span>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">Formateur</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6 cursor-pointer" onclick="openTutorial('equipe', 'Travailler en équipe', 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'Collaboration et partage dans vos équipes')">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Travailler en équipe</h3>
                                    <p class="text-gray-600 mt-1">Collaboration et partage dans vos équipes</p>
                                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                        <span>8 min</span>
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">Avancé</span>
                                    </div>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lecteur vidéo -->
            <div id="tutorial-player" class="hidden">
                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <button onclick="backToList()" class="flex items-center text-gray-600 hover:text-gray-800 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Retour aux tutoriels
                        </button>
                        <h2 id="video-title" class="text-lg font-semibold text-gray-900"></h2>
                    </div>
                    <div class="aspect-video">
                        <iframe id="tutorial-iframe"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                    <div class="p-4">
                        <p id="video-description" class="text-gray-700"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function openTutorial(id, title, url, description) {
            document.getElementById('tutorial-list').classList.add('hidden');
            document.getElementById('tutorial-player').classList.remove('hidden');

            document.getElementById('video-title').textContent = title;
            document.getElementById('video-description').textContent = description;
            document.getElementById('tutorial-iframe').src = url;
        }

        function backToList() {
            document.getElementById('tutorial-player').classList.add('hidden');
            document.getElementById('tutorial-list').classList.remove('hidden');

            // Reset iframe src to stop video
            document.getElementById('tutorial-iframe').src = '';
        }

        // Handle browser back button
        window.addEventListener('popstate', function(event) {
            if (document.getElementById('tutorial-player').classList.contains('hidden')) {
                // Already on list view
                return;
            }
            backToList();
            history.replaceState(null, null, window.location.pathname);
        });
    </script>
</body>
</html>
