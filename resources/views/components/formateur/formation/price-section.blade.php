<div class="text-center pb-4 border-b border-gray-100">
  <div class="text-sm font-medium text-gray-500 mb-2">Prix de la formation</div>
  <div class="text-5xl font-bold text-indigo-600 mb-1">
    {{ number_format($formation->money_amount ?? 0, 0, ',', ' ') }}
  </div>
  <div class="text-xl font-semibold text-indigo-500">€</div>
</div>