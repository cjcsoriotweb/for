{{-- Formation Catalogue Card Component --}}
@props(['formation', 'team', 'isVisible' => false])

<div
  class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-6 shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 hover:border-blue-300 dark:hover:border-blue-600">

  {{-- Background decoration --}}
  <div
    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-full -translate-y-16 translate-x-16 transition-transform duration-300 group-hover:scale-110">
  </div>

  {{-- Header with icon --}}
  <div class="relative flex items-start justify-between mb-6">
    <div class="flex items-center gap-4">
      <div
        class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg transition-transform duration-300 group-hover:scale-110">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
      <div class="flex-1">
        <h3
          class="text-xl font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
          {{ $formation->title }}
        </h3>
        <div class="flex items-center gap-2">
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 dark:from-blue-900 dark:to-purple-900 dark:text-blue-200">
            {{ $formation->level ?? 'Débutant' }}
          </span>
          @if($isVisible ?? false)
          <span
            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900 dark:to-emerald-900 dark:text-green-200">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"></path>
            </svg>
            Activée
          </span>
          @else
          <span
            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 dark:from-gray-900 dark:to-slate-900 dark:text-gray-200">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"></path>
            </svg>
            Non activée
          </span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Description --}}
  <div class="relative mb-6">
    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed line-clamp-3">
      {{ $formation->description }}
    </p>
  </div>

  {{-- Content Stats --}}
  <div class="relative mb-6">
    <div class="flex flex-wrap gap-3">
      @if($formation->lessons_count > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
          {{ $formation->lessons_count }} leçon{{ $formation->lessons_count > 1 ? 's' : '' }}
        </span>
      </div>
      @endif

      @if($formation->video_count > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
          </path>
        </svg>
        <span class="text-sm font-medium text-red-700 dark:text-red-300">
          {{ $formation->video_count }} vidéo{{ $formation->video_count > 1 ? 's' : '' }}
        </span>
      </div>
      @endif

      @if($formation->quiz_count > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
          </path>
        </svg>
        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">
          {{ $formation->quiz_count }} quiz
        </span>
      </div>
      @endif

      @if($formation->text_count > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
        <span class="text-sm font-medium text-green-700 dark:text-green-300">
          {{ $formation->text_count }} texte{{ $formation->text_count > 1 ? 's' : '' }}
        </span>
      </div>
      @endif

      @if($formation->learners_count > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
          </path>
        </svg>
        <span class="text-sm font-medium text-orange-700 dark:text-orange-300">
          {{ $formation->learners_count }} apprenant{{ $formation->learners_count > 1 ? 's' : '' }}
        </span>
      </div>
      @endif

      {{-- Duration Badge --}}
      @php
      $duration = $formation->total_duration_minutes ?? 0;
      $hours = floor($duration / 60);
      $minutes = $duration % 60;
      $durationText = '';
      if ($hours > 0) {
      $durationText .= $hours . 'h';
      }
      if ($minutes > 0) {
      $durationText .= ($hours > 0 ? ' ' : '') . $minutes . 'min';
      }
      if (empty($durationText)) {
      $durationText = '0min';
      }
      @endphp

      @if($duration > 0)
      <div class="flex items-center gap-2 px-3 py-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
          </path>
        </svg>
        <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
          {{ $durationText }}
        </span>
      </div>
      @endif
    </div>
  </div>

  {{-- Actions --}}
  <div class="relative flex items-center justify-end">
    <a href="{{ route('organisateur.formations.show', [$team, $formation]) }}"
      class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-3 py-2 text-sm font-medium text-blue-700 transition-all hover:bg-blue-50 dark:border-blue-600 dark:bg-gray-800 dark:text-blue-300">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
        </path>
      </svg>
      Voir
    </a>
  </div>
</div>