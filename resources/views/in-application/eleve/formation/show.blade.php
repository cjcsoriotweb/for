<x-eleve-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-eleve.notification-messages />

    @if($studentFormationService->isFormationCompleted(Auth::user(),$formationWithProgress))
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
    <livewire:ai.formation-chat :formation-id="$formationWithProgress->id" />
  @endauth
</x-eleve-layout>
