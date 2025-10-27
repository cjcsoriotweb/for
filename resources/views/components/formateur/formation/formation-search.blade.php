@props(['search', 'formations'])

<div class="mb-12">
  <div class="relative max-w-lg">
    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher une formation..."
      class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" />
    @if($search)
    <button wire:click="clearSearch"
      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    @endif
  </div>

  @if($search)
  <div class="mt-3 text-sm text-gray-600">
    {{ $formations->count() }} résultat{{ $formations->count() > 1 ? 's' : '' }} pour "{{ $search }}"
  </div>
  @endif
</div>