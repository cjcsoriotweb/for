@props(['stats'])

<div class="mb-12">
  <!-- Dashboard Header -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Tableau de bord</h2>
      <p class="text-gray-600">Aperçu de vos performances et statistiques</p>
    </div>
    <div class="flex items-center space-x-3">
      <div class="text-right">
        <p class="text-sm text-gray-500">Dernière mise à jour</p>
        <p class="text-sm font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
      </div>
      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
      </div>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Formations -->
    <div class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100/50 hover:border-blue-200/70 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
      <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 rounded-2xl"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_formations'] }}</p>
            <p class="text-xs text-gray-500">total</p>
          </div>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Formations</h3>
        <p class="text-sm text-gray-600">{{ $stats['active_formations'] }} actives</p>
        <div class="mt-3 w-full bg-blue-200 rounded-full h-1.5">
          <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full transition-all duration-1000"
               style="width: {{ $stats['total_formations'] > 0 ? min(100, ($stats['active_formations'] / $stats['total_formations']) * 100) : 0 }}%"></div>
        </div>
      </div>
    </div>

    <!-- Total Learners -->
    <div class="group relative bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-100/50 hover:border-emerald-200/70 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
      <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-500/5 rounded-2xl"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_learners']) }}</p>
            <p class="text-xs text-gray-500">étudiants</p>
          </div>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Étudiants inscrits</h3>
        <p class="text-sm text-gray-600">Total des apprenants</p>
        <div class="mt-3 flex items-center space-x-2">
          <div class="flex-1 w-full bg-emerald-200 rounded-full h-1.5">
            <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-1.5 rounded-full transition-all duration-1000"
                 style="width: {{ $stats['total_learners'] > 0 ? min(100, $stats['avg_completion_rate']) : 0 }}%"></div>
          </div>
          <span class="text-xs font-medium text-gray-700">{{ $stats['avg_completion_rate'] }}%</span>
        </div>
      </div>
    </div>

    <!-- Completion Rate -->
    <div class="group relative bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100/50 hover:border-purple-200/70 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
      <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-2xl"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['avg_completion_rate'] }}%</p>
            <p class="text-xs text-gray-500">moyenne</p>
          </div>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Taux de completion</h3>
        <p class="text-sm text-gray-600">Progression moyenne</p>
        <div class="mt-3 relative">
          <div class="w-full bg-purple-200 rounded-full h-1.5">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-1.5 rounded-full transition-all duration-1000"
                 style="width: {{ $stats['avg_completion_rate'] }}%"></div>
          </div>
          <div class="absolute -top-1 left-0 w-3 h-3 bg-purple-500 rounded-full animate-pulse"
               style="left: calc({{ $stats['avg_completion_rate'] }}% - 6px)"></div>
        </div>
      </div>
    </div>

    <!-- Total Lessons -->
    <div class="group relative bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100/50 hover:border-orange-200/70 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
      <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-2xl"></div>
      <div class="relative z-10">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_lessons']) }}</p>
            <p class="text-xs text-gray-500">modules</p>
          </div>
        </div>
        <h3 class="font-semibold text-gray-900 mb-1">Modules crées</h3>
        <p class="text-sm text-gray-600">{{ $stats['recent_formations'] }} formations récentes</p>
        <div class="mt-3 flex items-center space-x-2">
          <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
          <span class="text-xs text-gray-600">30 derniers jours</span>
        </div>
      </div>
    </div>
  </div>


</div>
