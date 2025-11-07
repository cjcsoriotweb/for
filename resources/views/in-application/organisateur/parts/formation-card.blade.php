{{-- Formation Card Component --}}
@props(['formation', 'team'])

@php
  $teamPivot = $formation->pivot ?? $formation->teams->firstWhere('id', $team->id)?->pivot;
  $usageQuota = $teamPivot->usage_quota ?? null;
  $usageConsumed = $teamPivot->usage_consumed ?? 0;
  $usageRemaining = !is_null($usageQuota) ? max($usageQuota - $usageConsumed, 0) : null;
@endphp

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
            {{ $formation->level ?? 'D&eacute;butant' }}
          </span>
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200">
            @if(!is_null($usageRemaining))
              {{ trans_choice(':count activation restante|:count activations restantes', $usageRemaining, ['count' => $usageRemaining]) }}
            @else
              Activations illimit&eacute;es
            @endif
          </span>
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

  {{-- Stats and Action --}}
  <div class="relative flex items-center justify-between">
    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
      @if($formation->lessons_count ?? false)
      <div class="flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
        {{ $formation->lessons_count }} leçon{{ ($formation->lessons_count ?? 1) > 1 ? 's' : '' }}
      </div>
      @endif
    </div>

    <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
      class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 text-sm font-medium text-white transition-all hover:from-blue-700 hover:to-purple-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
        </path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
        </path>
      </svg>
      Voir les étudiants
    </a>
  </div>
</div>

