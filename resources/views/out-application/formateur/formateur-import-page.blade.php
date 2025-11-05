<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Importer du contenu
                        </h1>
                        <p class="text-gray-600 mt-2 text-lg">Importez vos formations depuis des fichiers externes</p>
                    </div>
                    <a href="{{ route('formateur.home') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>

            <!-- Messages de feedback -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-green-800 font-medium">Import réussi</h4>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-red-800 font-medium">Erreur d'import</h4>
                            <p class="text-red-700 text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Import Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- JSON Import -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Import JSON</h3>
                            <p class="text-sm text-gray-600">Format structuré</p>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm mb-4">
                        Importez vos formations depuis un fichier JSON avec une structure complète incluant chapitres, leçons et contenu.
                    </p>
                    <form action="{{ route('formateur.import.json') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fichier JSON</label>
                            <input type="file" name="json_file" accept=".json"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                            Importer JSON
                        </button>
                    </form>
                </div>

                <!-- CSV Import -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Import CSV</h3>
                            <p class="text-sm text-gray-600">Données tabulaires</p>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm mb-4">
                        Importez vos formations depuis un fichier CSV avec colonnes pour titre, description, contenu, etc.
                    </p>
                    <form action="{{ route('formateur.import.csv') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fichier CSV</label>
                            <input type="file" name="csv_file" accept=".csv"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        </div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-medium py-2 px-4 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200">
                            Importer CSV
                        </button>
                    </form>
                </div>

                <!-- SCORM Import -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Import SCORM</h3>
                            <p class="text-sm text-gray-600">Paquet standard</p>
                        </div>
                    </div>
                    <p class="text-gray-700 text-sm mb-4">
                        Importez vos formations depuis un paquet SCORM (.zip) conforme aux standards e-learning.
                    </p>
                    <form action="{{ route('formateur.import.scorm') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fichier SCORM (.zip)</label>
                            <input type="file" name="scorm_file" accept=".zip"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        </div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white font-medium py-2 px-4 rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200">
                            Importer SCORM
                        </button>
                    </form>
                </div>
            </div>

            <!-- Import History -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historique des imports
                </h3>

                <div class="space-y-4">
                    <!-- Empty state for import history -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Aucun import effectué</h4>
                        <p class="text-gray-600">
                            Vos imports précédents apparaîtront ici une fois que vous aurez importé du contenu.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Aide et formats supportés
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Format JSON</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Structure attendue pour l'import JSON :
                        </p>
                        <pre class="bg-white p-3 rounded-lg text-xs text-gray-800 overflow-x-auto"><code>{
  "title": "Ma Formation",
  "description": "Description...",
  "chapters": [
    {
      "title": "Chapitre 1",
      "lessons": [
        {
          "title": "Leçon 1",
          "type": "text",
          "content": "Contenu de la leçon..."
        },
        {
          "title": "Vidéo explicative",
          "type": "video",
          "content": "https://example.com/video.mp4",
          "duration_minutes": 15
        }
      ]
    }
  ]
}</code></pre>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Format CSV</h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Colonnes attendues pour l'import CSV :
                        </p>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li><strong>title:</strong> Titre de la formation</li>
                            <li><strong>description:</strong> Description</li>
                            <li><strong>chapter_title:</strong> Titre du chapitre</li>
                            <li><strong>lesson_title:</strong> Titre de la leçon</li>
                            <li><strong>content_type:</strong> text/video/quiz</li>
                            <li><strong>content:</strong> Contenu de la leçon</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
