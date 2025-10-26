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
    class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 opacity-0 transition-opacity group-hover:opacity-100">
    </div>
    <div class="relative flex items-center">
      <div class="flex-shrink-0">
        <div
          class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 text-white shadow-lg">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
            </path>
          </svg>
        </div>
      </div>
      <div class="ml-5 flex-1">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total élèves</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
      </div>
    </div>
    <div
      class="absolute -bottom-1 -right-1 h-16 w-16 rounded-full bg-gradient-to-br from-gray-200/50 to-gray-300/50 dark:from-gray-700/50 dark:to-gray-600/50">
    </div>
  </div>

  {{-- Completed Students Card --}}
  <div
    class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-green-50 to-emerald-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-500/10 opacity-0 transition-opacity group-hover:opacity-100">
    </div>
    <div class="relative flex items-center">
      <div class="flex-shrink-0">
        <div
          class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <div class="ml-5 flex-1">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Terminées</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
      </div>
    </div>
    <div
      class="absolute -bottom-1 -right-1 h-16 w-16 rounded-full bg-gradient-to-br from-green-200/50 to-emerald-300/50 dark:from-green-700/50 dark:to-emerald-600/50">
    </div>
  </div>

  {{-- In Progress Students Card --}}
  <div
    class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-cyan-100 p-6 shadow-lg transition-all hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 opacity-0 transition-opacity group-hover:opacity-100">
    </div>
    <div class="relative flex items-center">
      <div class="flex-shrink-0">
        <div
          class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
      </div>
      <div class="ml-5 flex-1">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">En cours</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['in_progress'] }}</p>
      </div>
    </div>
    <div
      class="absolute -bottom-1 -right-1 h-16 w-16 rounded-full bg-gradient-to-br from-blue-200/50 to-cyan-300/50 dark:from-blue-700/50 dark:to-cyan-600/50">
    </div>
  </div>

  {{-- Monthly Cost Card --}}
  @if($type === 'students' && isset($stats['monthly_cost']))
  <a href="{{ route('organisateur.formations.students.cost', [$team, $formation]) }}"
    class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-50 to-pink-100 p-6 shadow-lg transition-all hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:from-gray-800 dark:to-gray-900 dark:focus:ring-offset-gray-900">
    <div
      class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 transition-opacity group-hover:opacity-100">
    </div>
    <div class="relative flex items-center">
      <div class="flex-shrink-0">
        <div
          class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <div class="ml-5 flex-1">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Coût ce mois-ci</p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['monthly_cost'], 0, ',', '
          ') }} €</p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $stats['monthly_enrollments'] }} inscription{{
          $stats['monthly_enrollments'] > 1 ? 's' : '' }}.</p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Restant {{ $team->money }}€.</p>
      </div>
    </div>
    <div
      class="absolute -bottom-1 -right-1 h-16 w-16 rounded-full bg-gradient-to-br from-purple-200/50 to-pink-300/50 transition-transform duration-200 group-hover:scale-110 dark:from-purple-700/50 dark:to-pink-600/50">
    </div>
  </a>
  @endif
</div>