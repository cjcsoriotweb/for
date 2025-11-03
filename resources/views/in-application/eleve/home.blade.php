<x-eleve-layout :team="$team">
  <div class="min-h-[calc(100vh-8rem)] bg-slate-950 text-slate-50">
    <div class="mx-auto max-w-6xl space-y-14 px-4 py-12 sm:px-6 lg:px-8">
      <x-eleve.notification-messages />

      <x-eleve.hello :team="$team" :current-formation="$formationsWithProgress" />
      <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" />
      <x-eleve.FormationChoice :team="$team" />
    </div>
  </div>
</x-eleve-layout>
