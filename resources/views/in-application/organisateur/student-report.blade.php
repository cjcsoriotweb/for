<x-organisateur-layout :team="$team">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <x-organisateur.parts.breadcrumb :team="$team" :formation="$formation"
        :student="isset($student) ? $student : null" />

      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          @php
          $studentDisplayName = $student->name ?? $student->email;
          $showStudentEmail = $student->email && (! $student->name || strcasecmp($student->name, $student->email) !==
          0);
          @endphp
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Rapport detaille</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            {{ $studentDisplayName }}
            @if($showStudentEmail)
            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</span>
            @endif
          </p>
        </div>

        <x-organisateur.parts.action-buttons :buttons="[
          ['type' => 'back', 'url' => route('organisateur.formations.students', [$team, $formation]), 'text' => 'Retour aux élèves'],
          ['type' => 'pdf', 'url' => route('organisateur.formations.students.report.pdf', [$team, $formation, $student]), 'text' => 'Voir le PDF'],
          ['type' => 'download', 'url' => route('organisateur.formations.students.report.pdf.download', [$team, $formation, $student]), 'text' => 'Télécharger le PDF']
        ]" />
      </div>
    </div>

    {{-- Tabbed content --}}
    <div x-data="{ activeTab: 'overview' }" x-cloak class="space-y-6">
      <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-3 sm:px-6 border-b border-gray-200 dark:border-gray-700">
          <nav class="flex flex-wrap gap-2" aria-label="Sections du rapport">
            @php
            $tabs = [
            'overview' => "Vue d'ensemble",
            'progress' => "Progression détaillée",
            'quizzes' => "Quiz",
            'activity' => "Activité",
            'documents' => "Documents"
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
          @include('in-application.organisateur.student-report.overview')
        </div>

        <div x-show="activeTab === 'progress'" x-transition>
          @include('in-application.organisateur.student-report.progress')
        </div>

        <div x-show="activeTab === 'quizzes'" x-transition>
          @include('in-application.organisateur.student-report.quizzes')
        </div>

        <div x-show="activeTab === 'activity'" x-transition>
          @include('in-application.organisateur.student-report.activity')
        </div>

        <div x-show="activeTab === 'documents'" x-transition>
          @include('in-application.organisateur.student-report.pdf')
        </div>
      </div>
    </div>
  </div>
</x-organisateur-layout>