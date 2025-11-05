@props([])

<div class="relative mb-12 pt-8">
  <!-- Background decoration -->
  <div class="absolute inset-0 bg-gradient-to-r from-blue-50/50 via-indigo-50/30 to-purple-50/50 rounded-3xl -mx-4 -my-4"></div>
  <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-2xl"></div>
  <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-indigo-400/10 to-cyan-400/10 rounded-full blur-xl"></div>

  <div class="relative z-10 flex items-center justify-between">
    <div class="flex-1">
      <div class="flex items-center space-x-4 mb-4">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <div>
          <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
            Mes formations
          </h1>
          <p class="text-gray-600 mt-1 text-lg">Gérez vos formations et contenus pédagogiques</p>
        </div>
      </div>

      <!-- Quick stats -->
      <div class="flex items-center space-x-6 text-sm">
        <div class="flex items-center space-x-2 px-3 py-2 bg-white/60 rounded-xl border border-gray-200/60">
          <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
          <span class="text-gray-700 font-medium">Plateforme active</span>
        </div>
        <div class="flex items-center space-x-2 px-3 py-2 bg-white/60 rounded-xl border border-gray-200/60">
          <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="text-gray-700">{{ now()->format('l j F Y') }}</span>
        </div>
        <div class="flex items-center space-x-2 px-3 py-2 bg-white/60 rounded-xl border border-gray-200/60">
          <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          <span class="text-gray-700">Bienvenue, {{ Auth::user()->name }}</span>
        </div>
      </div>
    </div>

    <div class="flex items-center space-x-4">
      <!-- Quick Actions Menu -->
      <div class="relative group">
        <button class="inline-flex items-center px-4 py-2 bg-white/80 hover:bg-white rounded-xl border border-gray-200/60 shadow-sm hover:shadow-md transition-all duration-200">
          <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
          </svg>
          Actions
          <svg class="w-4 h-4 ml-2 text-gray-500 group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200/60 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
          <div class="p-2">
            <a href="{{ route('formateur.formations.create') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors">
              <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Nouvelle formation
            </a>
            <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
              <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
              </svg>
              Importer contenu
            </a>
            <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors">
              <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              Voir analyses
            </a>
          </div>
        </div>
      </div>

      <!-- Main CTA Button -->
      <a href="{{ route('formateur.formations.create') }}"
        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:via-purple-700 hover:to-blue-800 transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 shadow-xl hover:shadow-2xl border border-white/20">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Créer une formation
      </a>
    </div>
  </div>
</div>
