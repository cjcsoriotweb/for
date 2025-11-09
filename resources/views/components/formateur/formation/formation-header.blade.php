@props([])

<div class="relative mb-12 pt-8">
  <!-- Background decoration -->
  <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 via-indigo-50/30 to-purple-50/50 rounded-3xl -mx-4 -my-4"></div>
  <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-2xl"></div>
  <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-indigo-400/10 to-cyan-400/10 rounded-full blur-xl"></div>

  <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 lg:gap-4">
    <div class="flex-1 text-center lg:text-left">
      <div class="flex flex-col sm:flex-row items-center sm:space-x-4 space-y-4 sm:space-y-0 mb-4 lg:mb-0">
        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
          <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
            Mes formations
          </h1>
          <p class="text-gray-600 mt-1 text-sm sm:text-base lg:text-lg">Gérez vos formations et contenus pédagogiques</p>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-end space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
      <!-- Secondary CTA Button -->
      <a href="{{ route('formateur.formations.create') }}"
        class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-200 hover:border-gray-300 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl text-sm sm:text-base w-full sm:w-auto justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Créer une formation
      </a>

      
    </div>
  </div>
</div>
