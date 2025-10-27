<div class="space-y-3">
  <a href="{{ route('formateur.formation.edit', $formation) }}"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-indigo-700 hover:text-white hover:bg-indigo-600 bg-indigo-50 rounded-xl transition-all duration-200 border border-indigo-200 hover:border-indigo-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
      </path>
    </svg>
    Modifier la formation
  </a>

  <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-blue-700 hover:text-white hover:bg-blue-600 bg-blue-50 rounded-xl transition-all duration-200 border border-blue-200 hover:border-blue-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
      </path>
    </svg>
    Gérer les chapitres
  </a>

  <a href="{{ route('formateur.formation.show', $formation) }}"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-white hover:bg-gray-600 bg-gray-50 rounded-xl transition-all duration-200 border border-gray-200 hover:border-gray-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
      </path>
    </svg>
    Voir la formation
  </a>
</div>