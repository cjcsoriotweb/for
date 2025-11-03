<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-gradient-to-br from-emerald-50 to-green-50 overflow-hidden shadow-sm sm:rounded-xl border border-emerald-100 mb-8">
        <div class="p-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <div class="flex items-center mb-4">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-200 mr-4">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Retour au tableau de bord
                </a>
              </div>
              <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                  </path>
                </svg>
                Gestion du contenu - {{ $formation->title }}
              </h1>
              <p class="text-gray-700 text-lg leading-relaxed mt-4">
                Les ajustements tarifaires sont desormais geres par l'equipe super-administrateur. Utilisez cette page pour acceder rapidement aux espaces de gestion de vos contenus pedagogiques.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                  </path>
                </svg>
              </div>
              <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">
                Structure
              </span>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Gerer les lecons</h2>
            <p class="text-gray-600 flex-grow">
              Organisez les chapitres, definissez le type de vos lecons et suivez l'evolution de vos contenus.
            </p>
            <div class="mt-6">
              <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition">
                Acceder a la gestion des lecons
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm0 4c3.866 0 7 1.343 7 3v2H5v-2c0-1.657 3.134-3 7-3zm-2 7h4v3h-4z">
                  </path>
                </svg>
              </div>
              <span class="text-xs font-semibold uppercase tracking-wide text-amber-500">
                Evaluation
              </span>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Gerer les quiz</h2>
            <p class="text-gray-600 flex-grow">
              Configurez le quiz d'entree de la formation, ajustez ses parametres et enrichissez le questionnaire.
            </p>
            <div class="mt-6">
              <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
                class="inline-flex items-center px-5 py-2.5 bg-amber-500 text-white text-sm font-medium rounded-lg shadow hover:bg-amber-600 transition">
                Acceder a la gestion des quiz
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="p-6 h-full flex flex-col">
            <div class="flex items-center justify-between mb-4">
              <div class="bg-rose-100 text-rose-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8l4 4v8a2 2 0 01-2 2H6a2 2 0 01-2-2V6z">
                  </path>
                </svg>
              </div>
              <span class="text-xs font-semibold uppercase tracking-wide text-rose-500">
                Vidéo
              </span>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Gerer les videos</h2>
            <p class="text-gray-600 flex-grow">
              Ajoutez ou mettez a jour les lecons video depuis la page de gestion des chapitres et centralisez vos supports.
            </p>
            <div class="mt-6">
              <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                class="inline-flex items-center px-5 py-2.5 bg-rose-500 text-white text-sm font-medium rounded-lg shadow hover:bg-rose-600 transition">
                Acceder a la gestion des videos
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-12 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-start">
              <div class="bg-emerald-100 text-emerald-600 rounded-full p-3 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                  </path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-gray-900">
                  Besoin d'un ajustement tarifaire ?
                </h3>
                <p class="text-gray-600 mt-2 leading-relaxed">
                  La tarification des formations est administree par l'equipe super-administrateur. Merci de leur transmettre votre demande via le support interne pour toute modification de prix ou de modalites de paiement.
                </p>
              </div>
            </div>
            <div class="flex-shrink-0">
              <a href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center px-5 py-2.5 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-50 transition">
                Retourner à la formation
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
