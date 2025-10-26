<x-organisateur-layout :team="$team">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <nav class="flex mb-4" aria-label="Fil d'Ariane">
        <ol class="flex items-center space-x-4">
          <li>
            <div>
              <a href="{{ route('organisateur.index', $team) }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                Formations
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
                class="ml-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                {{ $formation->title }}
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <span class="ml-4 text-gray-500 dark:text-gray-400">Rapport - {{ $student->name }}</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Rapport détaillé</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $student->name }} — {{ $student->email }}</p>
        </div>

        <div class="flex flex-wrap gap-3">
          <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
              </path>
            </svg>
            Retour aux élèves
          </a>

          <a href="{{ route('organisateur.formations.students.report.pdf', [$team, $formation, $student]) }}"
            target="_blank"
            class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
              </path>
            </svg>
            Voir le PDF
          </a>

          <a href="{{ route('organisateur.formations.students.report.pdf.download', [$team, $formation, $student]) }}"
            class="inline-flex items-center px-3 py-2 border border-green-300 dark:border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
            Télécharger le PDF
          </a>
        </div>
      </div>
    </div>

    {{-- Tabbed content --}}
    <div x-data="{ activeTab: 'overview' }" x-cloak class="space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-3 sm:px-6 border-b border-gray-200 dark:border-gray-700">
          <nav class="flex flex-wrap gap-2" aria-label="Sections du rapport">
            @php
            $tabs = [
            'overview' => 'Vue d’ensemble',
            'progress' => 'Progression détaillée',
            'quizzes' => 'Quiz',
            'activity' => 'Activité',
            'documents' => 'Documents'
            ];
            @endphp
            @foreach($tabs as $tabKey => $tabLabel)
            <button type="button" class="px-4 py-2 text-sm font-medium rounded-md transition" :class="activeTab === '{{ $tabKey }}'
                ? 'bg-blue-600 text-white shadow'
                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
              @click="activeTab = '{{ $tabKey }}'">
              {{ $tabLabel }}
            </button>
            @endforeach
          </nav>
        </div>
      </div>

      <div>
        <div x-show="activeTab === 'overview'" x-transition>
          @include('clean.organisateur.student-report.overview')
        </div>

        <div x-show="activeTab === 'progress'" x-transition>
          @include('clean.organisateur.student-report.progress')
        </div>

        <div x-show="activeTab === 'quizzes'" x-transition>
          @include('clean.organisateur.student-report.quizzes')
        </div>

        <div x-show="activeTab === 'activity'" x-transition>
          @include('clean.organisateur.student-report.activity')
        </div>

        <div x-show="activeTab === 'documents'" x-transition>
          @include('clean.organisateur.student-report.pdf')
        </div>
      </div>
    </div>
  </div>
</x-organisateur-layout>