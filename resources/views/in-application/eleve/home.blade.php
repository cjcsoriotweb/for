<x-eleve-layout :team="$team">
  <div class="min-h-[calc(100vh-8rem)] bg-white text-slate-950">
    <div class="mx-auto max-w-6xl space-y-14 px-4 py-12 sm:px-6 lg:px-8">
      <x-eleve.notification-messages />

    

      <div class="grid grid-cols-2 gap-20">
        <div>
          <x-eleve.FormationContinue :team="$team" :formations="$formationsWithProgress" :availableFormationsCount="$availableFormationsCount" />

        </div>
        <div>
      <x-eleve.FormationChoice :team="$team" />

        </div>
      </div>

      <x-eleve.team.managers-panel :team="$team" :managers="$teamManagers" />

      
    </div>
  </div>
</x-eleve-layout>
