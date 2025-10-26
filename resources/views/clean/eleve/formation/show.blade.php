<x-eleve-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-eleve.notification-messages />

    @if($studentFormationService->isFormationCompleted(Auth::user(),$formationWithProgress))
    @include('clean.eleve.formation.congratulation')
    @else
    <x-eleve.formation-header :formation="$formationWithProgress" :progress="$progress" />

    <x-eleve.formation-chapters :formation="$formationWithProgress" :team="$team" />

    <x-eleve.formation-actions :team="$team" :formation="$formationWithProgress" :progress="$progress" />
    @endif
  </div>
</x-eleve-layout>
