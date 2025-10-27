<x-app-layout>
  <!-- Formation Details -->
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
      <!-- Breadcrumb -->
      <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
              <a href="{{
                                    route(
                                        'formateur.formation.show',
                                        $formation
                                    )
                                }}" class="text-sm text-gray-700 hover:text-indigo-600">
                {{ $formation->title }}
              </a>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
                </svg>
                <a href="{{
                                        route(
                                            'formateur.formation.chapter.edit',
                                            [$formation, $chapter]
                                        )
                                    }}" class="text-sm text-gray-700 hover:text-indigo-600 ml-1">
                  Chapitre {{ $chapter->position }}
                </a>
              </div>
            </li>
            <li>
              <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm text-gray-500 ml-1">Modifier la Vidéo</span>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                </path>
              </svg>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-900">
                Modifier la Vidéo
              </h2>
              <p class="text-gray-600 mt-1">
                Modifiez votre contenu vidéo existant
              </p>
            </div>
          </div>

          <form method="POST" action="{{
                            route(
                                'formateur.formation.chapter.lesson.video.update',
                                [$formation, $chapter, $lesson]
                            )
                        }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            <!-- Video Title -->
            <div>
              <label for="video_title" class="block text-sm font-medium text-gray-700 mb-2">
                Titre de la Vidéo *
              </label>
              <input type="text" id="video_title" name="video_title"
                value="{{ old('video_title', $videoContent->title) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_title') border-red-500 @enderror"
                placeholder="Ex: Introduction à Laravel" required />
              @error('video_title')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <!-- Video Description -->
            <div>
              <label for="video_description" class="block text-sm font-medium text-gray-700 mb-2">
                Description de la Vidéo
              </label>
              <textarea id="video_description" name="video_description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_description') border-red-500 @enderror"
                placeholder="Décrivez brièvement le contenu de cette vidéo...">{{ old("video_description", $videoContent->description) }}</textarea>
              @error('video_description')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <!-- Video Source Selection -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">
                Source de la Vidéo *
              </label>
              <div class="space-y-3">
                <label class="flex items-center">
                  <input type="radio" name="video_source" value="upload" {{ old('video_source', $videoContent->video_url
                  ? 'url' : 'upload') === 'upload' ? 'checked' : '' }}
                  class="text-indigo-600
                  focus:ring-indigo-500"
                  onclick="toggleVideoSource('upload')" />
                  <span class="ml-2 text-sm text-gray-700">Téléverser un fichier vidéo</span>
                </label>
              </div>
              @error('video_source')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>


            <!-- URL Input -->
            <div id="url-input"
              class="{{ old('video_source', $videoContent->video_url ? 'url' : 'upload') === 'url' ? '' : 'hidden' }}">
              <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                URL de la Vidéo *
              </label>
              <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $videoContent->video_url) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_url') border-red-500 @enderror"
                placeholder="https://exemple.com/video.mp4" />
            </div>


            <!-- Video Preview Section -->
            <div class="bg-gray-50 rounded-lg p-4">
              <h3 class="text-sm font-medium text-gray-900 mb-3">
                Aperçu de la Vidéo
              </h3>
              <div id="video-preview-container" class="space-y-3">
                @if($videoContent->video_url)
                <!-- URL Video Preview -->
                <div class="aspect-video bg-black rounded-lg overflow-hidden">
                  @if($videoContent->video_path)
                  <video class="w-full h-full" controls preload="metadata">
                    <source src="{{ asset('storage/' . $videoContent->video_path) }}" type="video/mp4" />
                    Votre navigateur ne supporte pas la
                    lecture de vidéos.
                  </video>
                  @else
                  <div class="w-full h-full flex items-center justify-center text-white bg-gray-800">
                    <div class="text-center">
                      <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                          clip-rule="evenodd" />
                      </svg>
                      <p class="text-sm">
                        Aucun aperçu disponible
                      </p>
                    </div>
                  </div>
                  @endif
                </div>
                @elseif($videoContent->video_path)
                <!-- Uploaded Video Preview -->
                <div style="width:100%;height:300px" class="aspect-video bg-black rounded-lg overflow-hidden">
                  <video class="h-full w-full" controls preload="metadata">
                    <source src="{{ asset('storage/' . $videoContent->video_path) }}" type="video/mp4" />
                    Votre navigateur ne supporte pas la
                    lecture de vidéos.
                  </video>
                </div>
                @else
                <!-- No Video Preview -->
                <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                  <div class="text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd" />
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
                @endif
              </div>
            </div>

            <!-- File Upload -->
            <div id="file-input"
              class="{{ old('video_source', $videoContent->video_url ? 'url' : 'upload') === 'upload' ? '' : 'hidden' }}">
              <label for="video_file" class="block text-sm font-medium text-gray-700 mb-2">
                Fichier Vidéo
              </label>
              <div
                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                <div class="space-y-1 text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path
                      d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <div class="flex text-sm text-gray-600">
                    <label for="video_file"
                      class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                      <span>Téléverser un fichier</span>
                      <input id="video_file" name="video_file" type="file" class="sr-only" accept="video/*" />
                    </label>
                    <p class="pl-1">ou glisser-déposer</p>
                  </div>
                  <p class="text-xs text-gray-500">
                    MP4, AVI, MOV, WebM jusqu'à 500MB
                  </p>
                  @if($videoContent->video_path)
                  <p class="text-xs text-green-600">
                    Fichier actuel:
                    {{ basename($videoContent->video_path) }}
                  </p>
                  @endif
                </div>
              </div>
              @error('video_file')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
            </div>

            <!-- Video Duration -->
            <div>
              <label for="video_duration" class="block text-sm font-medium text-gray-700 mb-2">
                Durée de la Vidéo (minutes)
              </label>
              <input type="number" id="video_duration" name="video_duration"
                value="{{ old('video_duration', $videoContent->duration_minutes) }}" min="1" max="300"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('video_duration') border-red-500 @enderror"
                placeholder="Ex: 45" />
              @error('video_duration')
              <p class="mt-1 text-sm text-red-600">
                {{ $message }}
              </p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">
                Durée approximative en minutes (optionnel)
              </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <a href="{{
                                    route('formateur.formation.show', [
                                        $formation
                                    ])
                                }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                ← Retour aux leçons
              </a>
              <div class="space-x-3">
                <button type="button"
                  class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  Annuler
                </button>
                <button type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  Mettre à Jour la Vidéo
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function toggleVideoSource(source) {
      const urlInput = document.getElementById("url-input");
      const fileInput = document.getElementById("file-input");

      if (source === "url") {
        urlInput.classList.remove("hidden");
        fileInput.classList.add("hidden");
      } else {
        urlInput.classList.add("hidden");
        fileInput.classList.remove("hidden");
      }
    }

    // Video preview functionality for edit form
    document.getElementById('video_url').addEventListener('input', function () {
      const videoUrl = this.value;
      const previewContainer = document.getElementById('video-preview-container');

      if (videoUrl.trim() === '') {
        // Show current video preview if exists, otherwise default
        @if ($videoContent -> video_path)
          previewContainer.innerHTML = `
                        <div class="aspect-video bg-black rounded-lg overflow-hidden">
                            <video class="w-full h-full" controls preload="metadata">
                                <source src="{{ asset('storage/' . $videoContent->video_path) }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>
                    `;
        @else
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
        @endif
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