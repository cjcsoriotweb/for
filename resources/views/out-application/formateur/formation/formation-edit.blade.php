<x-app-layout>
  <div class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8 rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50 to-blue-50 shadow-sm">
        <div class="p-8">
          <a href="{{ route('formateur.formation.show', $formation) }}"
            class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-800 hover:bg-indigo-100">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour à la formation
          </a>

          <div class="mt-6 flex flex-col gap-3">
            <h1 class="text-3xl font-bold text-gray-900">
              Modifier les informations de {{ $formation->title }}
            </h1>
            <p class="text-base text-gray-700">
              Choisissez la section à mettre à jour. Chaque modification dispose désormais de sa propre page pour vous permettre de travailler plus rapidement et sans surcharge.
            </p>
          </div>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2">
        <a href="{{ route('formateur.formation.edit.title', $formation) }}"
          class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-lg">
          <div class="flex items-start gap-4">
            <span
              class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 transition group-hover:bg-indigo-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16.862 4.487l1.687 1.688-9.193 9.193a2 2 0 01-.878.515l-3.12.78.78-3.12a2 2 0 01.515-.878l9.193-9.193z" />
              </svg>
            </span>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Titre de la formation</h2>
              <p class="mt-1 text-sm text-gray-600">
                Adaptez le titre pour refléter l'actualité de votre contenu.
              </p>
            </div>
          </div>
          <span class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-600">
            Modifier le titre
            <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </a>

        <a href="{{ route('formateur.formation.edit.description', $formation) }}"
          class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-lg">
          <div class="flex items-start gap-4">
            <span
              class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600 transition group-hover:bg-purple-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 16h8M8 12h4m-2-8l8 4v6a8 8 0 11-16 0V8l8-4z" />
              </svg>
            </span>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Description</h2>
              <p class="mt-1 text-sm text-gray-600">
                Décrivez précisément la formation pour informer vos apprenants.
              </p>
            </div>
          </div>
          <span class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-600">
            Modifier la description
            <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </a>

        <a href="{{ route('formateur.formation.edit.cover', $formation) }}"
          class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-lg md:col-span-2">
          <div class="flex items-start gap-4">
            <span
              class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 transition group-hover:bg-amber-200">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5h18M3 19h18M5 5v14m14-14v14M9 9l6 3-6 3V9z" />
              </svg>
            </span>
            <div>
              <h2 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">Image de couverture</h2>
              <p class="mt-1 text-sm text-gray-600">
                Mettez à jour le visuel affiché sur la page de présentation et dans les catalogues.
              </p>
            </div>
          </div>
          <span class="mt-6 inline-flex items-center text-sm font-semibold text-indigo-600">
            Modifier le visuel
            <svg class="ml-1 h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </span>
        </a>
      </div>
    </div>
  </div>
</x-app-layout>
