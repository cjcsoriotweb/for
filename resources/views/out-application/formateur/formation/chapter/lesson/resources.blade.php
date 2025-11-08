<x-app-layout>
  <div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
      @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <p class="font-semibold mb-2">Veuillez corriger les points suivants :</p>
        <ul class="list-disc list-inside text-sm space-y-1">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
      @endif

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-500 mb-1">
            {{ $formation->title }} • Chapitre {{ $chapter->position }}
          </p>
          <h1 class="text-2xl font-semibold text-gray-900">
            Ressources du module : {{ $lesson->getName() }}
          </h1>
          <p class="text-sm text-gray-500 mt-1">
            Ajoutez ou remplacez les documents mis à disposition des apprenants.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Retour aux chapitres
          </a>
          @if(! empty($editRoute))
          <a href="{{ $editRoute }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 transition">
            Modifier le contenu
          </a>
          @endif
        </div>
      </div>

      @if($supportsInline)
      <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
          <h2 class="text-lg font-semibold text-gray-900">Ressource affichée dans la leçon</h2>
          <p class="text-sm text-gray-500 mt-1">
            Ce PDF est intégré directement dans la leçon (mode lecture).
          </p>
        </div>

        <div class="px-6 py-6 space-y-6">
          @if($inlineAttachment)
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ $inlineAttachment->name }}</p>
              <p class="text-xs text-gray-500">{{ strtoupper($inlineAttachment->mime_type) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ Storage::disk('public')->url($inlineAttachment->file_path) }}" target="_blank"
                class="inline-flex items-center px-3 py-2 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                Prévisualiser
              </a>
          @endif

      <div class="bg-white shadow-sm border border-gray-200 rounded-2xl {{ $supportsInline ? 'mt-6' : '' }}">
        <div class="px-6 py-4 border-b border-gray-100">
          <h2 class="text-lg font-semibold text-gray-900">Fichiers téléchargeables</h2>
          <p class="text-sm text-gray-500">Ces documents apparaissent dans la section “Ressources” de la leçon.</p>
        </div>

        <div class="px-6 py-6 space-y-6">
          <form method="POST"
            action="{{ route('formateur.formation.chapter.lesson.resources.store', [$formation, $chapter, $lesson]) }}"
            enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid md:grid-cols-{{ $supportsInline ? 2 : 1 }} gap-6">
              @if($supportsInline)
              <div>
                <label for="inline_document" class="block text-sm font-medium text-gray-700 mb-2">
                  Remplacer / ajouter le PDF intégré
                </label>
                <input type="file" id="inline_document" name="inline_document" accept="application/pdf"
                  class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                <p class="mt-1 text-xs text-gray-500">PDF uniquement • 20&nbsp;Mo maximum.</p>
              </div>
              @endif

              <div>
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                  Ajouter des fichiers à télécharger
                </label>
                <input type="file" id="attachments" name="attachments[]" multiple
                  class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                <p class="mt-1 text-xs text-gray-500">Tous formats autorisés • 20&nbsp;Mo maximum par fichier.</p>
              </div>
            </div>

            <div class="flex justify-end">
              <button type="submit"
                class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 transition">
                Mettre à jour les ressources
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="bg-white shadow-sm border border-gray-200 rounded-2xl">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">Fichiers disponibles</h2>
            <p class="text-sm text-gray-500">Ces documents apparaissent dans la section “Ressources” de la leçon.</p>
          </div>
          <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
            {{ $downloadAttachments->count() }} fichier{{ $downloadAttachments->count() > 1 ? 's' : '' }}
          </span>
        </div>

        @if($downloadAttachments->isEmpty())
        <div class="p-8 text-center text-sm text-gray-500">
          Aucun fichier n'a encore été ajouté pour ce module.
        </div>
        @else
        <ul class="divide-y divide-gray-100">
          @foreach($downloadAttachments as $attachment)
          <li class="px-6 py-4 flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ $attachment->name }}</p>
              <p class="text-xs text-gray-500">{{ strtoupper($attachment->mime_type) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ Storage::disk('public')->url($attachment->file_path) }}" target="_blank"
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                Télécharger
              </a>
              <form method="POST"
                action="{{ route('formateur.formation.chapter.lesson.resources.delete', [$formation, $chapter, $lesson, $attachment]) }}"
                onsubmit="return confirm('Supprimer ce fichier ?');">
                @csrf
                @method('delete')
                <button type="submit"
                  class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                  Supprimer
                </button>
              </form>
            </div>
          </li>
          @endforeach
        </ul>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>

