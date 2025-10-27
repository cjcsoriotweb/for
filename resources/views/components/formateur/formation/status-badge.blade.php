<div class="pt-4 border-t border-gray-100">
  <div class="flex items-center justify-center">
    @if($formation->active)
    <span
      class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
      <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>
      Formation Active
    </span>
    @else
    <span
      class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
      <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
      Formation Inactive
    </span>
    @endif
  </div>
</div>