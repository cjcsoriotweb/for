<div
  class="bg-white dark:bg-slate-800 rounded-xl shadow-elevated border border-slate-200 dark:border-slate-700 card-hover overflow-hidden flex flex-col">
  <!-- Header with icon -->
  <div class="p-6 border-b border-slate-100 dark:border-slate-700">
    <div class="flex items-start justify-between mb-3">
      <div
        class="w-12 h-12 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-700 rounded-lg flex items-center justify-center flex-shrink-0">
        <span class="material-symbols-outlined text-xl text-primary-600 dark:text-primary-400">business_center</span>
      </div>
      @if($isEnrolled)
      <span
        class="bg-green-100 text-green-800 dark:bg-green-900 dark:bg-opacity-30 dark:text-green-400 text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1 shrink-0">
        <span class="material-symbols-outlined text-sm">timelapse</span>
        En cours
      </span>
      @endif
    </div>
    <h2 class="text-xl font-semibold text-slate-900 dark:text-white leading-tight">{{ $formation->title }}</h2>
    <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm leading-relaxed">{{ $formation->description }}</p>
  </div>

  <!-- Content -->
  <div class="p-6 flex-1 flex flex-col">
    <!-- Progress bar for enrolled formations -->
    @if($isEnrolled && $formationUser)
    @php
    // Calculate progress dynamically based on completed lessons
    $totalLessons = $formation->chapters->pluck('lessons')->flatten()->count();
    $completedLessons = 0;

    foreach ($formation->chapters as $chapter) {
    foreach ($chapter->lessons as $lesson) {
    $lessonProgress = $lesson->learners()->where('user_id', auth()->user()->id)->first();
    if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
    $completedLessons++;
    }
    }
    }

    $progressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
    @endphp
    <div class="mb-6">
      <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Progression</span>
        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $progressPercent }}%</span>
      </div>
      <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
        <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500"
          style="width: {{ $progressPercent }}%"></div>
      </div>
    </div>
    @endif

    <!-- Admin controls -->
    @if($isAdminMode)
    <div class="mt-auto">
      @if($formation->pivot_active ?? false)
      <x-forms.admin.formations.disable :team="$team" :formation="$formation" />
      @else
      <x-forms.admin.formations.enable :team="$team" :formation="$formation" />
      @endif
    </div>
    @endif

    <!-- Student actions -->
    @if(!$isAdminMode)
    <div class="mt-auto space-y-3">
      @if($isEnrolled && $formationUser)
      <a href="{{ route('application.eleve.formations.continue', [$team, $formation]) }}"
        class="w-full inline-flex items-center justify-center py-3 px-4 border-2 border-blue-200 dark:border-blue-700 shadow-sm text-sm font-semibold rounded-xl text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 hover:bg-blue-100 dark:hover:bg-blue-900 dark:bg-opacity-40 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 hover:scale-105">
        <span class="material-symbols-outlined text-base mr-2">play_arrow</span>
        Continuer
      </a>
      @else
      <a href="{{ route('application.eleve.formations.preview', [$team, $formation]) }}"
        class="w-full inline-flex items-center justify-center py-3 px-4 shadow-sm text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200 hover:scale-105 hover:shadow-lg">
        <span class="material-symbols-outlined text-base mr-2">rocket_launch</span>
        Commencer
      </a>
      @endif
    </div>
    @endif
  </div>
</div>
