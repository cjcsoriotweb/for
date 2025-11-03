<x-eleve-layout :team="$team">
  <div
    class="relative isolate min-h-[calc(100vh-8rem)] overflow-hidden bg-slate-950 text-slate-50">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900/85 to-slate-950"></div>
    <div
      class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(56,189,248,0.18),transparent_60%)]"></div>
    <div
      class="pointer-events-none absolute inset-y-0 left-1/2 hidden w-[1400px] -translate-x-1/2 rotate-[12deg] bg-[linear-gradient(90deg,transparent,_rgba(99,102,241,0.18)_45%,rgba(192,132,252,0.12)_70%,transparent)] opacity-70 blur-xl lg:block">
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 py-10 space-y-16 sm:px-6 md:py-16 lg:px-8">
      <x-eleve.notification-messages />

      <x-eleve.hello :team="$team" :current-formation="$formationsWithProgress" />
      <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" />
      <x-eleve.FormationChoice :team="$team" />
    </div>
  </div>
</x-eleve-layout>
