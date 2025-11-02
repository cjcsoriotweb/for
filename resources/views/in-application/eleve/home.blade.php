<x-eleve-layout :team="$team">
  <div class="relative isolate min-h-[calc(100vh-8rem)] text-slate-50">
    <div class="absolute inset-0 "></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 space-y-14">
      <x-eleve.notification-messages />

      <x-eleve.hello :team="$team" :current-formation="$formationsWithProgress" />
      <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" />
      <x-eleve.FormationChoice :team="$team" />
    </div>
  </div>
</x-eleve-layout>
