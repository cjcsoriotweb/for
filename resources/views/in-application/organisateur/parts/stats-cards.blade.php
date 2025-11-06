{{-- Statistics Cards Component --}}
@props(['stats' => [], 'type' => 'default'])

@php
$defaultStats = [
'total' => 0,
'completed' => 0,
'in_progress' => 0,
'monthly_cost' => 0,
'monthly_enrollments' => 0
];
$stats = array_merge($defaultStats, $stats);
@endphp

<div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
  {{-- Total Students Card --}}
  <div
    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-50 to-gray-100 p-6 shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <div class="relative flex items-center justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-slate-500 to-gray-600 text-white shadow-lg transition-transform duration-300 group-hover:scale-110">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total étudiants</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
          </div>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
          <div
            class="bg-gradient-to-r from-slate-400 to-gray-500 h-1.5 rounded-full transition-all duration-500 w-full">
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute -bottom-2 -right-2 h-20 w-20 rounded-full bg-gradient-to-br from-slate-200/30 to-gray-300/30 dark:from-slate-700/30 dark:to-gray-600/30 transition-transform duration-300 group-hover:scale-110">
    </div>
  </div>

  {{-- Completed Students Card --}}
  <div
    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-green-100 p-6 shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-500/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <div class="relative flex items-center justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg transition-transform duration-300 group-hover:scale-110">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Terminées</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['completed']) }}</p>
          </div>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
          <div
            class="bg-gradient-to-r from-emerald-400 to-green-500 h-1.5 rounded-full transition-all duration-500 {{ $stats['total'] > 0 ? 'w-[' . round(($stats['completed'] / $stats['total']) * 100) . '%]' : 'w-0' }}">
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute -bottom-2 -right-2 h-20 w-20 rounded-full bg-gradient-to-br from-emerald-200/30 to-green-300/30 dark:from-emerald-700/30 dark:to-green-600/30 transition-transform duration-300 group-hover:scale-110">
    </div>
  </div>

  {{-- In Progress Students Card --}}
  <div
    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-cyan-100 p-6 shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
    </div>
    <div class="relative flex items-center justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg transition-transform duration-300 group-hover:scale-110">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
              </path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En cours</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['in_progress']) }}</p>
          </div>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
          @php
          $progressPercent = $stats['total'] > 0 ? round(($stats['in_progress'] / $stats['total']) * 100) : 0;
          $progressClass = match(true) {
          $progressPercent >= 75 => 'w-3/4',
          $progressPercent >= 50 => 'w-1/2',
          $progressPercent >= 25 => 'w-1/4',
          default => 'w-0'
          };
          @endphp
          <div
            class="bg-gradient-to-r from-blue-400 to-cyan-500 h-1.5 rounded-full transition-all duration-500 {{ $progressClass }}">
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute -bottom-2 -right-2 h-20 w-20 rounded-full bg-gradient-to-br from-blue-200/30 to-cyan-300/30 dark:from-blue-700/30 dark:to-cyan-600/30 transition-transform duration-300 group-hover:scale-110">
    </div>
  </div>

</div>