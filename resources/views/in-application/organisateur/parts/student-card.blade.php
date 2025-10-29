{{-- Student Card Component --}}
@props(['summary', 'formation', 'team'])

<article
  class="flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
  <div class="space-y-4">
    <div class="flex items-start gap-3">
      <span
        class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold uppercase text-blue-600 dark:bg-blue-900 dark:text-blue-200">
        {{ $summary->initials }}
      </span>

      <div>
        <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $summary->student->name }}</h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $summary->student->email }}</p>
      </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
      <span
        class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-medium {{ $summary->status_classes }}">
        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
            clip-rule="evenodd"></path>
        </svg>
        {{ $summary->status_label }}
      </span>

      @if($summary->score_percent)
      <span
        class="inline-flex items-center gap-1 rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M4 3a2 2 0 00-2 2v9.586A2 2 0 003.414 17L7 13.414a2 2 0 012.828 0L13.414 17A2 2 0 0015 17V5a2 2 0 00-2-2H4z" />
        </svg>
        {{ $summary->score_percent }}%
      </span>
      @endif
    </div>

    <div class="space-y-3">
      <div>
        <div class="mb-1 flex justify-between text-xs font-medium text-gray-500 dark:text-gray-400">
          <span>Progression</span>
          <span>{{ $summary->progress_percent }}%</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
          <div class="h-2 bg-blue-500 dark:bg-blue-400" style="width: {{ $summary->progress_percent }}%"></div>
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
          {{ $summary->completed_lessons }} / {{ $summary->progress_base }} lecons terminees
        </p>
      </div>

      <dl class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
        <div class="flex items-center justify-between">
          <dt class="font-medium text-gray-500 dark:text-gray-400">Inscrit</dt>
          <dd>{{ $summary->enrolled_at ? $summary->enrolled_at->format('d/m/Y @ H:i') : 'Non renseigne' }}</dd>
        </div>

        @if($summary->last_seen_at)
        <div class="flex items-center justify-between">
          <dt class="font-medium text-gray-500 dark:text-gray-400">Derniere connexion</dt>
          <dd>{{ $summary->last_seen_at->format('d/m/Y @ H:i') }}</dd>
        </div>
        @endif

        @if($summary->completed_at)
        <div class="flex items-center justify-between">
          <dt class="font-medium text-gray-500 dark:text-gray-400">Terminee</dt>
          <dd>{{ $summary->completed_at->format('d/m/Y @ H:i') }}</dd>
        </div>
        @endif
      </dl>
    </div>
  </div>

  <div class="mt-4 border-t border-dashed border-gray-200 pt-4 dark:border-gray-700">
    @if($summary->has_time)
    <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
      <div>
        <p class="font-medium text-gray-500 dark:text-gray-400">Temps passe</p>
        <p>{{ $summary->total_hours }}h {{ $summary->total_minutes }}min</p>
      </div>
      <div class="text-right">
        <p class="font-medium text-gray-500 dark:text-gray-400">Lecons consultees</p>
        <p>{{ $summary->lesson_count }}</p>
      </div>
    </div>
    @else
    <p class="text-sm text-gray-500 dark:text-gray-400">Aucune activite enregistree pour le moment.</p>
    @endif

    <a href="{{ route('organisateur.formations.students.report', [$team, $formation, $summary->student]) }}"
      class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      Rapport detaille
    </a>
  </div>
</article>