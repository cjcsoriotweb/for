{{-- Breadcrumb Navigation Component --}}
@props(['team', 'formation' => null, 'student' => null, 'currentPage' => null])

<nav class="mb-6 flex" aria-label="Fil d'Ariane">
  <ol class="flex items-center space-x-4">
    <li>
      <a href="{{ route('organisateur.index', $team) }}"
        class="group flex items-center gap-2 text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
        <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0">
          </path>
        </svg>
        Formations
      </a>
    </li>

    @if($formation)
    <li>
      <div class="flex items-center">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
          aria-hidden="true">
          <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-4 text-gray-500 dark:text-gray-400">{{ $formation->title }}</span>
      </div>
    </li>
    @endif

    @if($student)
    <li>
      <div class="flex items-center">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
          aria-hidden="true">
          <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-4 text-gray-500 dark:text-gray-400">Rapport - {{ $student->name }}</span>
      </div>
    </li>
    @endif

    @if($currentPage)
    <li>
      <div class="flex items-center">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
          aria-hidden="true">
          <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
        </svg>
        <span class="ml-4 text-gray-700 dark:text-gray-200">{{ $currentPage }}</span>
      </div>
    </li>
    @endif
  </ol>
</nav>