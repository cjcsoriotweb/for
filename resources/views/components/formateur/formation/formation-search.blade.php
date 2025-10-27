@props(['search', 'formations'])

<div class="mb-12">
  <div class="relative max-w-lg">
    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
      <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
      </svg>
    </div>
    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher une formation..."
      class="group block w-full pl-12 pr-12 py-4 bg-white/80 backdrop-blur-sm border-2 border-gray-200/60 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-900 placeholder-gray-500 shadow-lg hover:shadow-xl focus:shadow-2xl text-lg" />
    @if($search)
    <button wire:click="clearSearch"
      class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 transition-all duration-200 group-hover:bg-red-50 rounded-r-2xl p-2 -m-2">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    @endif
  </div>

  @if($search)
  <div class="mt-4 p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-gray-200/60 shadow-lg">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
          <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <div>
          <span class="text-gray-600 text-sm">
            Recherche active: "<span class="font-semibold text-gray-900 bg-blue-100 px-2 py-1 rounded-lg">{{
              $search
              }}</span>"
          </span>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <div class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
          {{ $formations->count() }}
          résultat{{ $formations->count() > 1 ? 's' : '' }}
        </div>
        <button wire:click="clearSearch"
          class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
          Effacer
        </button>
      </div>
    </div>
  </div>
  @endif
</div>