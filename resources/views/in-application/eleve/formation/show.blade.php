<x-eleve-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-eleve.notification-messages />

    @php
        $isCompleted = $studentFormationService->isFormationCompleted(Auth::user(), $formationWithProgress);
    @endphp

    @if(isset($isValidated) && $isValidated)
    @include('in-application.eleve.formation.congratulation')
    @else
    <!-- Layout responsive : grid sur PC, stack sur mobile -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Présentation + ressources à gauche sur PC -->
      <div class="order-1 lg:order-1 space-y-8">
        <x-eleve.formation-header :formation="$formationWithProgress" :progress="$progress" />

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

      <!-- Actions rapides puis timeline à droite sur PC -->
      <div class="order-2 lg:order-2 space-y-8">
        <x-eleve.formation-actions :team="$team" :formation="$formationWithProgress" :progress="$progress" />

        <x-eleve.formation-timeline :formation="$formationWithProgress" />
      </div>
    </div>
    @endif
  </div>



@auth
@endauth
</x-eleve-layout>
