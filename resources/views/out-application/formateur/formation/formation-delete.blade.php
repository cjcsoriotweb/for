<x-app-layout>
  <div class="py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white border border-red-200 rounded-2xl shadow-sm">
        <div class="p-6 sm:p-8">
          <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>

            <h1 class="mt-4 text-2xl font-bold text-slate-900">
              Supprimer la formation
            </h1>

            <p class="mt-2 text-sm text-slate-600">
              Cette action est irréversible. Toutes les données de la formation seront supprimées définitivement.
            </p>
          </div>

          <div class="mt-8">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">
                    Formation à supprimer : {{ $formation->title }}
                  </h3>
                  <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                      <li>Tous les chapitres et leçons seront supprimés</li>
                      <li>Tous les quiz et questions seront supprimés</li>
                      <li>Tous les contenus texte et vidéo seront supprimés</li>
                      <li>Tous les documents de fin de formation seront supprimés</li>
                      <li>Les équipes associées ne seront pas affectées</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <form method="POST" action="{{ route('formateur.formation.delete.destroy', $formation) }}" class="mt-8">
            @csrf
            @method('DELETE')

            <div>
              <label for="confirmation_code" class="block text-sm font-medium text-slate-700">
                Code de confirmation
              </label>
              <div class="mt-1">
                <input type="number" name="confirmation_code" id="confirmation_code" min="1" max="6000"
                  class="block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                  placeholder="Tapez le code de confirmation"
                  required>
              </div>
              <p class="mt-2 text-sm text-slate-500">
                Pour confirmer la suppression, tapez le code suivant : <strong class="text-red-600">{{ $confirmationCode }}</strong>
              </p>
              @error('confirmation_code')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="mt-8 flex items-center justify-between">
              <a href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                Annuler
              </a>

              <button type="submit"
                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Supprimer définitivement
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
