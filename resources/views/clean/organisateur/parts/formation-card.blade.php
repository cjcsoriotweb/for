{{-- Formation Card Component --}}
@props(['formation', 'team'])

<div
  class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
  <div class="flex items-start justify-between mb-4">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $formation->title }}</h3>
      <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ $formation->description }}</p>
      <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
        <span
          class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-medium">
          {{ $formation->level ?? 'Debutant' }}
        </span>
      </div>
    </div>
  </div>

  <div class="flex items-center justify-between">
    <div class="text-sm text-gray-500 dark:text-gray-400">
      @php
      $enrolledCount = $formation->learners_count ?? 0;
      @endphp
      {{ $enrolledCount }} eleve{{ $enrolledCount > 1 ? 's' : '' }} inscrit{{ $enrolledCount > 1 ? 's' : '' }}
    </div>

    <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
      class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
        </path>
      </svg>
      Voir eleves
    </a>
  </div>
</div>