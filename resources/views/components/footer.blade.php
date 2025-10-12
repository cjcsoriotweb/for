<footer class="mt-10 border-t bg-white px-6 py-4 text-sm text-gray-600">
  <div class="mx-auto flex max-w-7xl items-center justify-between">
    <div>
      {{-- Priorité : nom d’équipe courante, sinon fallback --}}
      <strong>{{  config('app.name') }}</strong>
        <span class="text-gray-500">—  {{ Auth::user()->currentTeam->name ?? '' }}</span>
    </div>
    @if(Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
      <div>
        <a href="{{ route('admin.index') }}" class="text-blue-600 hover:underline">Admin</a>
      </div>
    @endif
    <div>© {{ now()->year }}</div>
  </div>
</footer>
