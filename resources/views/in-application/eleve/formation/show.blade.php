<x-eleve-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-eleve.notification-messages />

    @php
        $isCompleted = $studentFormationService->isFormationCompleted(Auth::user(),$formationWithProgress);
        if ($isCompleted) {
            // Marquer la formation comme terminée dans la base de données
            $formationWithProgress->learners()->syncWithoutDetaching([
                Auth::user()->id => [
                    'status' => 'completed',
                    'completed_at' => now(),
                    'last_seen_at' => now(),
                ],
            ]);
        }
    @endphp

    @if($isCompleted)
    @include('in-application.eleve.formation.congratulation')
    @else
    <!-- Layout responsive : grid sur PC, stack sur mobile -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Présentation + Contenu à gauche sur PC -->
      <div class="order-1 lg:order-1 space-y-8">
        <x-eleve.formation-header :formation="$formationWithProgress" :progress="$progress" />

        <x-eleve.formation-timeline :formation="$formationWithProgress" />

      </div>

      <!-- Contenu principal à droite sur PC -->
      <div class="order-2 lg:order-2 space-y-8">
        <x-eleve.formation-actions :team="$team" :formation="$formationWithProgress" :progress="$progress" />

        <div id="resources-section">
          @include('in-application.eleve.formation.partials.resources', [
            'formationDocuments' => $formationDocuments ?? collect(),
            'lessonResources' => $lessonResources ?? collect(),
            'isFormationCompleted' => $isFormationCompleted ?? false,
            'team' => $team,
            'formation' => $formationWithProgress,
          ])
        </div>
      </div>
    </div>
    @endif
  </div>



@auth
@endauth
</x-eleve-layout>
