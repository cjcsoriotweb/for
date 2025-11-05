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
    <x-eleve.formation-header :formation="$formationWithProgress" :progress="$progress" />

    <x-eleve.formation-chapters :formation="$formationWithProgress" :team="$team" />

    <x-eleve.formation-actions :team="$team" :formation="$formationWithProgress" :progress="$progress" />

    @include('in-application.eleve.formation.partials.resources', [
      'formationDocuments' => $formationDocuments ?? collect(),
      'lessonResources' => $lessonResources ?? collect(),
      'isFormationCompleted' => $isFormationCompleted ?? false,
      'team' => $team,
      'formation' => $formationWithProgress,
    ])
    @endif
  </div>



@auth
@endauth
</x-eleve-layout>
