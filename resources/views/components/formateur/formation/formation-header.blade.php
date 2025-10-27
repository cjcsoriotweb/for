@props([])

<div class="flex items-center justify-between mb-12 pt-8">
  <div class="flex-1">
    <div class="flex items-center space-x-4 mb-3">
      <div
        class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
      <div>
        <h1
          class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
          Espace formations
        </h1>
        <p class="text-gray-600 mt-1 text-lg">
          Gérez vos formations
        </p>
      </div>
    </div>
  </div>
  <a href="{{ route('formateur.formations.create') }}"
    class="group inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 text-white font-semibold rounded-2xl hover:from-indigo-700 hover:via-purple-700 hover:to-indigo-800 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-xl hover:shadow-2xl border border-white/20 backdrop-blur-sm">
    <svg class="w-5 h-5 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
    <span class="relative">
      Créer une formation
      <div
        class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
      </div>
    </span>
  </a>
</div>