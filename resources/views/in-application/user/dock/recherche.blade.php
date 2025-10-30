<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="p-6 h-full">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 text-indigo-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.35-4.35"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Recherche avancée</h2>
                    <p class="text-gray-600">Trouvez rapidement du contenu dans votre plateforme</p>
                </div>

                <div class="bg-indigo-50 rounded-lg p-6 text-center">
                    <p class="text-indigo-800 text-lg">Recherche intelligente</p>
                    <p class="text-indigo-700 mt-2">Parcourez et trouvez facilement vos formations et ressources</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
