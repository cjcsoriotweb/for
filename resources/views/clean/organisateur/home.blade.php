<x-organisateur-layout :team="$team">

  {{-- Balance Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Montant</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">Votre application as {{ $team->money }}€</p>
      <p>
        <a href="#" class="btn bg-blue-500 p-2 text-white rounded-xl hover:bg-blue-800">Cliquez ici pour recharger</a>
      </p>
    </div>
  </div>

  {{-- Users Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Utilisateurs</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">Gerez les utilisateurs de votre organisation.</p>
    </div>
  </div>

  {{-- Formations Section --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Formations</h1>
      <p class="mt-2 text-gray-600 dark:text-gray-400">Gerez les formations de votre equipe.</p>
    </div>

    @if($formations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($formations as $formation)
      <x-organisateur.parts.formation-card :formation="$formation" :team="$team" />
      @endforeach
    </div>
    @else
    <x-organisateur.parts.empty-state icon="formation" title="Aucune formation"
      description="Aucune formation n'est disponible pour le moment." />
    @endif
  </div>

</x-organisateur-layout>