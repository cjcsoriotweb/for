<!-- Formations Disponibles -->
<section class="py-16 bg-gray-50 bg-white mt-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
        Vos formations disponibles
      </h2>
    </div>

    @if($availableFormations->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($availableFormations as $formation) @php $isEnrolled =
      app(\App\Services\Formation\StudentFormationService::class)
      ->isEnrolledInFormation(auth()->user(), $formation, $team ?? null);
      $progress =
      app(\App\Services\Formation\StudentFormationService::class)
      ->getStudentProgress(auth()->user(), $formation); @endphp

      <div class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-200">
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
            </path>
          </svg>
        </div>

        <h3 class="font-semibold text-lg mb-2">
          {{ $formation->title }}
        </h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
          {{ $formation->description ?? 'Formation disponible' }}
        </p>

        @if($isEnrolled && $progress)
        <div class="mb-4">
          <div class="flex justify-between text-sm text-gray-600 mb-1">
            <span>Progression</span>
            <span>{{ $progress["progress_percent"] }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-primary h-2 rounded-full" style="width: {{ $progress['progress_percent'] }}%"></div>
          </div>
        </div>

        <div class="flex items-center justify-between mb-4">
          <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
            Inscrit
          </span>
          <span class="text-sm font-semibold text-primary">
            {{ $formation->money_amount ? $formation->money_amount . '€' : 'Gratuit' }}
          </span>
        </div>

        <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
          class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors text-center block">
          Continuer la formation
        </a>
        @else

        @if($team->money > $formation->money_amount)
        <div class="flex items-center justify-between mb-4">
          <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
            Disponible
          </span>
          <span class="text-sm font-semibold text-primary">
            {{ $formation->money_amount ? $formation->money_amount . '€' : 'Gratuit' }}
          </span>
        </div>

        <form method="POST"
          action="{{ route('eleve.formation.enroll', ['team' => $team ?? 1, 'formation' => $formation->id]) }}"
          class="inline">
          @csrf
          <button type="submit"
            class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-center block">
            S'inscrire à cette formation
          </button>
        </form>

        @else
        <div class="flex-col items-center justify-between mb-4">
          <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">
            Solde application insuffisant
          </span>
        </div>
        @endif


        @endif
      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-12">
      <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">
        Aucune formation disponible
      </h3>
      <p class="text-gray-500 mb-6">
        Il n'y a actuellement aucune formation disponible pour votre
        équipe.
      </p>
    </div>
    @endif
  </div>
</section>