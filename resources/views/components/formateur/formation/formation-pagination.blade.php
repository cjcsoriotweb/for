@props(['formations'])

@if($formations->hasPages())
<div
  class="mt-12 flex items-center justify-between bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-gray-200/60 shadow-lg">
  <div class="text-sm text-gray-700 font-medium">
    Affichage de <span class="text-indigo-600 font-semibold">{{ $formations->firstItem() }}</span> à
    <span class="text-indigo-600 font-semibold">{{ $formations->lastItem() }}</span> sur
    <span class="text-indigo-600 font-semibold">{{ $formations->total() }}</span> formations
  </div>
  <div class="flex items-center space-x-1">
    {{ $formations->links() }}
  </div>
</div>
@endif