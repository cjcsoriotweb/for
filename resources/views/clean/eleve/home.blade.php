<x-eleve-layout :team="$team">
  {{-- Messages de notification --}}
  @if(session('success'))
  <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    {{ session("success") }}
  </div>
  @endif @if(session('warning'))
  <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
    {{ session("warning") }}
  </div>
  @endif @if(session('error'))
  <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    {{ session("error") }}
  </div>
  @endif

  <x-eleve.hello :team="$team" :current-formation="$formationsWithProgress" />
  <x-eleve.FormationChoice :team="$team" />
  <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" />
</x-eleve-layout>
