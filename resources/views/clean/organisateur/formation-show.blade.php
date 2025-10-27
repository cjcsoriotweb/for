<x-organisateur-layout :team="$team">

  {{-- Header --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
      {{-- Navigation --}}
      <div class="mb-8">
        <a href="{{ route('organisateur.catalogue', $team) }}"
          class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
          </svg>
          Retour au catalogue
        </a>
      </div>

      {{-- Main Header Content --}}
      <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
        <div class="flex-1">
          {{-- Title and Status --}}
          <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
              <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                {{ $formation->title }}
              </h1>
              <div class="flex-shrink-0">
                @if($isVisible)
                <span
                  class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 dark:from-green-900 dark:to-emerald-900 dark:text-green-200">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"></path>
                  </svg>
                  Activée pour {{ $team->name }}
                </span>
                @else
                <span
                  class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 dark:from-gray-900 dark:to-slate-900 dark:text-gray-200">
                  <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                      clip-rule="evenodd"></path>
                  </svg>
                  Non activée pour {{ $team->name }}
                </span>
                @endif
              </div>
            </div>
          </div>

          {{-- Level and Price --}}
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <span
              class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 dark:from-blue-900 dark:to-purple-900 dark:text-blue-200 w-fit">
              {{ $formation->level ?? 'Débutant' }}
            </span>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ number_format($formation->money_amount, 0, ',', ' ') }} €
            </div>
          </div>
        </div>

        {{-- Decorative Icon --}}
        <div class="flex-shrink-0 lg:flex lg:items-start lg:justify-center">
          <div class="relative">
            <div
              class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full blur opacity-30 scale-150">
            </div>
            <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-full">
              <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Description --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Description</h2>
      <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
        {{ $formation->description }}
      </p>
    </div>
  </div>

  {{-- Content Overview --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      {{-- Videos --}}
      <div
        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-2xl p-6 border border-red-200 dark:border-red-800">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-red-600 dark:text-red-400 mb-1">Vidéos</p>
            <p class="text-3xl font-bold text-red-900 dark:text-red-100">{{ $videoCount }}</p>
            <p class="text-xs text-red-500 dark:text-red-300 mt-1">contenu{{ $videoCount > 1 ? 's' : '' }} vidéo</p>
          </div>
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
              </path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Quizzes --}}
      <div
        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-1">Quiz</p>
            <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $quizCount }}</p>
            <p class="text-xs text-blue-500 dark:text-blue-300 mt-1">évaluation{{ $quizCount > 1 ? 's' : '' }}</p>
          </div>
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
              </path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Text Content --}}
      <div
        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200 dark:border-green-800">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">Textes</p>
            <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $textCount }}</p>
            <p class="text-xs text-green-500 dark:text-green-300 mt-1">contenu{{ $textCount > 1 ? 's' : '' }} texte</p>
          </div>
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
          </div>
        </div>
      </div>

      {{-- Total Lessons --}}
      <div
        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200 dark:border-purple-800">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-purple-600 dark:text-purple-400 mb-1">Total leçons</p>
            <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $formationWithDetails->lessons_count
              }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-300 mt-1">leçon{{ $formationWithDetails->lessons_count >
              1 ? 's' : '' }} au total</p>
          </div>
          <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-500 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Formation Content --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Contenu de la formation</h2>

      @if($formationWithDetails->chapters->count() > 0)
      <div class="space-y-6">
        @foreach($formationWithDetails->chapters as $chapter)
        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
              {{ $chapter->title ?? 'Chapitre ' . ($loop->iteration) }}
            </h3>
            <span
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 dark:from-blue-900 dark:to-purple-900 dark:text-blue-200">
              {{ $chapter->lessons->count() }} leçon{{ $chapter->lessons->count() > 1 ? 's' : '' }}
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($chapter->lessons as $lesson)
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
              @if($lesson->lessonable_type === 'App\\Models\\VideoContent')
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-500 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                  </path>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Vidéo</p>
              </div>
              @elseif($lesson->lessonable_type === 'App\\Models\\Quiz')
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                  </path>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quiz</p>
              </div>
              @elseif($lesson->lessonable_type === 'App\\Models\\TextContent')
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-500 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Contenu texte</p>
              </div>
              @else
              <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-500 text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                  </path>
                </svg>
              </div>
              <div class="flex-1">
                <p class="font-medium text-gray-900 dark:text-white">{{ $lesson->title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Leçon</p>
              </div>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
      @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun contenu</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Cette formation n'a pas encore de contenu.
        </p>
      </div>
      @endif
    </div>
  </div>

  {{-- Actions --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      @if($isVisible)
      <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-sm font-medium text-white transition-all hover:from-blue-700 hover:to-purple-700 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
          </path>
        </svg>
        Voir les étudiants inscrits
      </a>
      @else
      <div
        class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-3 text-sm font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
          </path>
        </svg>
        Formation non activée pour votre équipe
      </div>
      @endif
    </div>
  </div>

</x-organisateur-layout>