<x-app-layout>
  <div class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-5">
          <div class="flex flex-col gap-3">
            <div class="flex items-center gap-3 text-sm text-indigo-600">
              <a href="{{ route('formateur.formation.edit', $formation) }}"
                class="inline-flex items-center font-medium hover:text-indigo-800 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour aux options de modification
              </a>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Modifier la description</h1>
              <p class="mt-1 text-sm text-gray-600">
                Présentez clairement les objectifs et le contenu de la formation.
              </p>
            </div>
          </div>
        </div>

        <div class="p-6">
          <form action="{{ route('formateur.formation.update.description', $formation) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description détaillée</label>
              <textarea
                id="description"
                name="description"
                rows="8"
                class="mt-2 w-full rounded-xl border border-gray-300 px-4 py-3 text-base shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Décrivez la formation et ses objectifs"
                required
              >{{ old('description', $formation->description) }}</textarea>
              @error('description')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
              <a
                href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:border-indigo-200 hover:text-indigo-700 transition-colors duration-200"
              >
                Annuler
              </a>
              <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
              >
                Enregistrer la description
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if (session('success'))
  <div
    class="fixed top-4 right-4 z-50 flex items-center rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-lg"
  >
    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    {{ session('success') }}
  </div>
  @endif
</x-app-layout>
