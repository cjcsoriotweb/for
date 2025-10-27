@props([])

<div class="text-center py-20">
  <!-- Enhanced empty state with animations -->
  <div class="relative">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="w-32 h-32 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full blur-2xl animate-pulse">
      </div>
    </div>

    <!-- Main icon with enhanced styling -->
    <div class="relative mx-auto w-32 h-32 mb-8">
      <div
        class="absolute inset-0 bg-gradient-to-br from-blue-100 via-indigo-100 to-purple-100 rounded-3xl transform rotate-6 animate-bounce">
      </div>
      <div class="absolute inset-1 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl transform -rotate-3"></div>
      <div
        class="relative w-full h-full bg-gradient-to-br from-white to-blue-50 rounded-3xl flex items-center justify-center shadow-xl border border-blue-100">
        <svg class="w-16 h-16 text-blue-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
    </div>

    <!-- Enhanced text content -->
    <div class="space-y-4">
      <h3
        class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
        Aucune formation trouvée
      </h3>
      <p class="text-gray-600 text-lg max-w-md mx-auto leading-relaxed">
        Vous n'avez pas encore créé de formations. Commencez par créer votre première formation pour partager vos
        connaissances.
      </p>

      <!-- Call to action -->
      <div class="pt-6">
        <a href="{{ route('formateur.formations.create') }}"
          class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 text-white font-semibold rounded-2xl hover:from-indigo-700 hover:via-purple-700 hover:to-indigo-800 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-xl hover:shadow-2xl border border-white/20">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Créer ma première formation
        </a>

        <!-- Additional help text -->
        <p class="text-sm text-gray-500 mt-4">
          Besoin d'aide ? Consultez notre guide de démarrage rapide.
        </p>
      </div>
    </div>
  </div>
</div>