<footer class="mt-10 border-t bg-white px-6 py-4 text-sm text-gray-600">
  <div class="mx-auto flex max-w-7xl items-center justify-between">
    <div>
      {{-- Priorité : nom d’équipe courante, sinon fallback --}}
      <strong>{{  config('app.name') }}</strong>
      @if(!empty($currentTeam))
        <span class="text-gray-500">—  {{ $currentTeam->name }}</span>
      @endif
    </div>
    <div>© {{ now()->year }}</div>
  </div>
</footer>
