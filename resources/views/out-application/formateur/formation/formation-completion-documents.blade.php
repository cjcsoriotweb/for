<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div
        class="bg-gradient-to-br from-indigo-50 to-blue-50 overflow-hidden shadow-sm sm:rounded-xl border border-indigo-100 mb-8">
        <div class="p-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <a href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-200 mr-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à  la formation
              </a>
              <h1 class="text-3xl font-bold text-gray-900 mb-3 flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                  </path>
                </svg>
                Documents de fin de formation - {{ $formation->title }}
              </h1>
              <p class="text-gray-700 text-lg leading-relaxed">
                Gérez les documents remis aux apprenants à la fin de la formation.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 md:p-8">
          <div class="flex items-start justify-between mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Ajouter un document</h2>
              <p class="text-sm text-gray-600 mt-1">
                Ajoutez les documents remis aux apprenants une fois la formation terminée.
              </p>
            </div>
          </div>

          <form action="{{ route('formateur.formation.completion-documents.store', $formation) }}" method="POST"
            enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
              <label for="document_title" class="block text-sm font-medium text-gray-700 mb-2">Titre du document</label>
              <input type="text" id="document_title" name="title" value="{{ old('title') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Ex. Attestation de formation" required>
              @error('title')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">Fichier</label>
              <input type="file" id="document_file" name="file"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                required>
              @error('file')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex justify-end">
              <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition-colors duration-200">
                Ajouter le document
              </button>
            </div>
          </form>

          <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents disponibles</h3>
            @if($formation->completionDocuments->isNotEmpty())
            <ul class="divide-y divide-gray-200">
              @foreach($formation->completionDocuments as $document)
              <li class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ $document->title }}</p>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ $document->original_name }} @if($document->size) - {{ number_format($document->size / 1024, 1) }} Ko @endif
                  </p>
                  @if($document->created_at)
                  <p class="text-xs text-gray-500 mt-1">
                    Ajouté le {{ $document->created_at->format('d/m/Y H:i') }}
                  </p>
                  @endif
                </div>
                <div class="mt-3 sm:mt-0 flex items-center gap-3">
                  <a href="{{ Storage::disk('public')->url($document->file_path) }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                    Télécharger
                  </a>
                  <form
                    action="{{ route('formateur.formation.completion-documents.destroy', [$formation, $document]) }}"
                    method="POST" onsubmit="return confirm('Supprimer ce document ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-200">
                      Supprimer
                    </button>
                  </form>
                </div>
              </li>
              @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">Aucun document de fin de formation pour le moment.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

