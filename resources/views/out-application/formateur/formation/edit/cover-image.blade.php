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
              <h1 class="text-2xl font-bold text-gray-900">Mettre à jour l'image de couverture</h1>
              <p class="mt-1 text-sm text-gray-600">
                Choisissez un visuel impactant pour illustrer la formation.
              </p>
            </div>
          </div>
        </div>

        <div class="p-6">
          <form action="{{ route('formateur.formation.update.cover', $formation) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 sm:grid-cols-2">
              <div>
                <span class="block text-sm font-medium text-gray-700">Image actuelle</span>
                <div class="mt-3 h-40 w-full overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                  <img
                    src="{{ $formation->cover_image_url }}"
                    alt="Image actuelle de la formation"
                    class="h-full w-full object-cover"
                    onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
                  />
                </div>
              </div>

              <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Nouvelle image</label>
                <input
                  type="file"
                  id="cover_image"
                  name="cover_image"
                  accept="image/*"
                  class="mt-2 block w-full cursor-pointer rounded-xl border border-dashed border-gray-300 px-4 py-3 text-sm text-gray-700 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  required
                />
                <p class="mt-2 text-xs text-gray-500">
                  Formats acceptés : JPEG, PNG, WEBP. Taille maximale : 4 Mo.
                </p>
                @error('cover_image')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
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
                Enregistrer le visuel
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
