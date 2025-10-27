<div class="space-y-3">
  <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-indigo-700 hover:text-white hover:bg-indigo-600 bg-indigo-50 rounded-xl transition-all duration-200 border border-indigo-200 hover:border-indigo-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
      </path>
    </svg>
    Gérer les chapitres
  </a>

  <button type="button" onclick="openEditContentModal()"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-blue-700 hover:text-white hover:bg-blue-600 bg-blue-50 rounded-xl transition-all duration-200 border border-blue-200 hover:border-blue-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
      </path>
    </svg>
    Modifier contenu
  </button>

  <button type="button" onclick="openEditPriceModal()"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium text-emerald-700 hover:text-white hover:bg-emerald-600 bg-emerald-50 rounded-xl transition-all duration-200 border border-emerald-200 hover:border-emerald-600">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
      </path>
    </svg>
    Modifier prix
  </button>

  <button type="button" onclick="toggleFormationStatus()"
    class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium {{ $formation->active ? 'text-red-700 hover:text-white hover:bg-red-600 bg-red-50 border-red-200 hover:border-red-600' : 'text-emerald-700 hover:text-white hover:bg-emerald-600 bg-emerald-50 border-emerald-200 hover:border-emerald-600' }} rounded-xl transition-all duration-200">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      @if($formation->active)
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
      </path>
      @else
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
      </path>
      @endif
    </svg>
    {{ $formation->active ? 'Désactiver' : 'Activer' }} la formation
  </button>
</div>