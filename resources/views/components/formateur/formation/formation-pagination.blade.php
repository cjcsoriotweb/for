@props(['formations'])

@if($formations->hasPages())
<div class="mt-8 flex items-center justify-between">
  <div class="text-sm text-gray-700">
    Affichage de {{ $formations->firstItem() }} à {{ $formations->lastItem() }} sur {{ $formations->total() }}
    formations
  </div>
  <div class="flex items-center space-x-1">
    {{ $formations->links() }}
  </div>
</div>
@endif