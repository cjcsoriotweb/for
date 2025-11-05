<div>
    {{-- Alerte d'erreurs --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Erreur!</strong>
            <span class="block sm:inline">Veuillez corriger les erreurs suivantes:</span>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Fil d'Ariane --}}
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('formateur.formation.show', $formation) }}" class="text-sm text-gray-700 hover:text-indigo-600">
                                {{ $formation->title }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}" class="text-sm text-gray-700 hover:text-indigo-600 ml-1">
                                    Chapitre {{ $chapter->position }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1">{{ $video_content ? 'Modifier la Vidéo' : 'Ajouter une Vidéo' }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 rounded-full p-3 mr-4">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $video_content ? 'Modifier la Vidéo' : 'Ajouter une Vidéo' }}
                            </h2>
                            <p class="text-gray-600 mt-1">
                                {{ $video_content ? 'Modifiez votre contenu vidéo existant' : 'Téléchargez ou intégrez une vidéo pour votre leçon' }}
                            </p>
                        </div>
                    </div>

                    {{-- Formulaire principal --}}
                    <form wire:submit.prevent="save" class="space-y-6">
                        {{-- Titre --}}
                        <div>
                            <label for="video_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de la Vidéo *
                            </label>
                            <input
                                type="text"
                                id="video_title"
                                wire:model.defer="video_title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_title') border-red-500 @enderror"
                                placeholder="Ex: Introduction aux concepts de base"
                                required
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Saisissez le titre et la description, puis cliquez sur « Ajouter la Vidéo » pour enregistrer.
                            </p>
                            @error('video_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="video_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description de la Vidéo
                            </label>
                            <textarea
                                id="video_description"
                                wire:model.defer="video_description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_description') border-red-500 @enderror"
                                placeholder="Décrivez brièvement le contenu de cette vidéo..."></textarea>
                            @error('video_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload fichier --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fichier Vidéo *
                            </label>

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                <div class="text-center">
                                    {{-- Animation de chargement --}}
                                    <div wire:loading wire:target="video_file" class="space-y-4">
                                        <div class="flex items-center justify-center">
                                            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <div class="space-y-2">
                                            <p class="text-sm font-medium text-gray-900">Téléchargement en cours…</p>
                                            <p class="text-xs text-gray-600">Veuillez patienter pendant l'upload du fichier.</p>
                                        </div>
                                    </div>

                                    {{-- Sélecteur de fichier --}}
                                    <div wire:loading.remove wire:target="video_file" class="space-y-1">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="video_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Sélectionner un fichier vidéo</span>
                                                <input
                                                    id="video_file"
                                                    wire:model="video_file"
                                                    type="file"
                                                    class="sr-only"
                                                    accept="video/*"
                                                />
                                            </label>
                                            <p class="pl-1">ou glisser-déposer</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            MP4, AVI, MOV, WebM jusqu'à 512MB
                                        </p>

                                        {{-- Statut sélection --}}
                                        @if($video_file)
                                            <div class="text-xs text-green-600 font-medium mt-2">
                                                ✅ Fichier sélectionné: {{ $video_file->getClientOriginalName() }}
                                                ({{ number_format($video_file->getSize() / 1024 / 1024, 2) }} MB)
                                            </div>
                                        @endif

                                        @if($video_content && $video_content->video_path)
                                            <p class="text-xs text-blue-600 mt-1">
                                                📁 Fichier actuel: {{ basename($video_content->video_path) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @error('video_file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Aperçu --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Aperçu de la Vidéo</h3>

                            <div id="video-preview-container" class="space-y-3">
{{-- Aperçu du fichier temporaire --}}
@if($video_file)

<p>Enregistez et actualisez.</p>
@elseif($video_content && $video_content->video_path)
    @php
        $existingKey = 'existing-'.($video_content->id ?? '0').'-'.optional($video_content->updated_at)->timestamp;
    @endphp
    {{-- Vidéo existante --}}
    <div class="aspect-video bg-black rounded-lg overflow-hidden" wire:key="preview-{{ $existingKey }}">
        <video class="w-full h-full" controls preload="metadata">
            <source src="{{ Storage::disk('public')->url($video_content->video_path) }}?v={{ time() }}" type="video/mp4" />
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
    </div>
    <div class="text-xs text-blue-600 font-medium">
        📁 Vidéo existante : {{ basename($video_content->video_path) }}
    </div>
@else
    {{-- Aucun aperçu --}}
    <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center" wire:key="preview-empty">
        <div class="text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm">Aucun aperçu disponible</p>
            <p class="text-xs mt-1">Sélectionnez un fichier vidéo pour voir l'aperçu</p>
        </div>
    </div>
@endif

                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('formateur.formation.show', $formation) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                ← Retour aux leçons
                            </a>
                            <div class="space-x-3">
                                <button
                                    type="button"
                                    wire:click="cancel"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Annuler
                                </button>

                                <button
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove>{{ $video_content ? 'Mettre à Jour' : 'Ajouter' }} la Vidéo</span>
                                    <span wire:loading>Sauvegarde...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div> {{-- /.p-6 --}}
            </div> {{-- /.card --}}
        </div>
    </div>
</div>
