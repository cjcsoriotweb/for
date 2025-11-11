<x-eleve-layout :team="$team">
    <div class="min-h-[calc(100vh-8rem)] bg-white text-slate-950">
        <div class="mx-auto max-w-6xl space-y-14 px-4 py-12 sm:px-6 lg:px-8">
            <x-eleve.notification-messages />



              @if($availableFormationsCount === 0)
              <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4">
                <div class="flex items-start gap-3">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Pas d'inquiétude, aucune formation disponible pour l'équipe</h3>
                    <p class="mt-1 text-sm text-red-700">
                      S'il s'agit d'une erreur contactez-vous (<a href="{{ route('user.tickets') }}" class="underline hover:text-amber-800">page ticket</a>).
                    </p>
                  </div>
                </div>
              </div>
              @endif
              
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
