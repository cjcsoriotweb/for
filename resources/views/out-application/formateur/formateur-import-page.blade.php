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

            <!-- Import ZIP -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 hover:shadow-xl transition-all duration-300">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900">Import ZIP</h3>
                        <p class="text-gray-600 mt-2">Formation complète avec fichiers médias</p>
                    </div>

                    <!-- Loading State -->
                    <div id="loading-state" class="hidden text-center py-8">
                        <div class="inline-flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mt-4">Import en cours...</h4>
                        <p class="text-gray-600 mt-2">Veuillez patienter pendant le traitement de votre fichier</p>
                    </div>

                    <!-- Import Form -->
                    <div id="import-form">
                        <form action="{{ route('formateur.import.zip') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="zip-import-form">
                            @csrf
                            <div>
                                <label for="zip_file" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sélectionnez votre fichier ZIP
                                </label>
                                <input type="file" name="zip_file" id="zip_file" accept=".zip" required
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-gray-500 mt-2">Taille maximale : 100 Mo</p>
                            </div>
                            <button type="submit" id="import-button"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                    Importer la formation
                                </span>
                            </button>
                        </form>
                    </div>
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
                    @if(isset($recentImports) && $recentImports->count() > 0)
                        @foreach($recentImports as $log)
                            <div class="flex items-start p-4 rounded-xl border {{ $log->status === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                <div class="flex-shrink-0 mr-3">
                                    @if($log->status === 'success')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium {{ $log->status === 'success' ? 'text-green-900' : 'text-red-900' }}">
                                            {{ $log->filename }}
                                        </h4>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $log->format === 'zip' ? 'bg-blue-100 text-blue-700' : ($log->format === 'json' ? 'bg-green-100 text-green-700' : 'bg-purple-100 text-purple-700') }}">
                                            {{ strtoupper($log->format) }}
                                        </span>
                                    </div>
                                    @if($log->formation)
                                        <a href="{{ route('formateur.formation.show', $log->formation) }}" 
                                           class="text-sm {{ $log->status === 'success' ? 'text-green-700 hover:text-green-800' : 'text-red-700' }} font-medium">
                                            {{ $log->formation->title }}
                                        </a>
                                    @endif
                                    @if($log->stats)
                                        <p class="text-xs {{ $log->status === 'success' ? 'text-green-600' : 'text-red-600' }} mt-1">
                                            @if(isset($log->stats['chapters_count']))
                                                {{ $log->stats['chapters_count'] }} chapitre(s), 
                                            @endif
                                            @if(isset($log->stats['lessons_count']))
                                                {{ $log->stats['lessons_count'] }} leçon(s)
                                            @endif
                                        </p>
                                    @endif
                                    @if($log->error_message)
                                        <p class="text-xs text-red-600 mt-1">
                                            Erreur : {{ $log->error_message }}
                                        </p>
                                    @endif
                                    <p class="text-xs {{ $log->status === 'success' ? 'text-green-500' : 'text-red-500' }} mt-1">
                                        {{ $log->created_at->diffForHumans() }}
                                        @if($log->file_size)
                                            • {{ number_format($log->file_size / 1024 / 1024, 2) }} Mo
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
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
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Guide d'import ZIP
                </h3>

                <div class="mb-6">
                    <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                        <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">ZIP</span>
                        Format ZIP - Formation complète
                    </h4>
                    <p class="text-sm text-gray-700 mb-4">
                        Le format ZIP permet d'importer une formation complète avec tous ses fichiers médias (vidéos, documents, etc.).
                        C'est le format recommandé pour les imports complexes.
                    </p>

                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <h5 class="font-medium text-gray-900 mb-3">Structure du fichier ZIP :</h5>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span><strong>orchestre.json</strong> - Fichier de configuration principal contenant la structure de la formation</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span><strong>Dossier media/</strong> - Contient toutes les vidéos, images et documents</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span><strong>Structure complète</strong> - Chapitres, leçons, quiz et contenu organisé</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h5 class="font-medium text-yellow-800 mb-1">Important</h5>
                            <p class="text-sm text-yellow-700">
                                Les formations importées sont <strong>désactivées par défaut</strong>. Pensez à les activer après vérification.
                                Les types de leçons acceptés sont : <code class="bg-yellow-100 px-1 rounded">text</code>,
                                <code class="bg-yellow-100 px-1 rounded">video</code>, et <code class="bg-yellow-100 px-1 rounded">quiz</code>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importForm = document.getElementById('zip-import-form');
            const importButton = document.getElementById('import-button');
            const importFormDiv = document.getElementById('import-form');
            const loadingState = document.getElementById('loading-state');

            importForm.addEventListener('submit', function(e) {
                // Afficher l'état de chargement
                importFormDiv.classList.add('hidden');
                loadingState.classList.remove('hidden');

                // Désactiver le bouton
                importButton.disabled = true;
                importButton.classList.add('opacity-50', 'cursor-not-allowed');

                // Le formulaire sera soumis normalement
            });
        });
    </script>
</x-app-layout>
