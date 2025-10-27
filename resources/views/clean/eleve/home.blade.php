<x-eleve-layout :team="$team">
  <div class="relative isolate min-h-[calc(100vh-8rem)] bg-gradient-to-b from-slate-900 via-slate-850 to-slate-800 text-slate-50">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.22),transparent_55%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.18),transparent_60%),linear-gradient(145deg,rgba(148,163,184,0.12),transparent_65%)]"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 space-y-14">
      <x-eleve.notification-messages />

      <x-eleve.hello :team="$team" :current-formation="$formationsWithProgress" />
      <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" />
      <x-eleve.FormationChoice :team="$team" />
    </div>
  </div>
</x-eleve-layout>
