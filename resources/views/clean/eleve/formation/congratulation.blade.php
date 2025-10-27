<div class="min-h-screen flex items-center justify-center p-4">
  <div class="max-w-2xl w-full">
    <x-eleve.notification-messages />

    <!-- Main Congratulation Card -->
    <div
      class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden transform hover:scale-105 transition-all duration-300">
      <!-- Celebration Header -->
      <div class="bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 p-8 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 bg-black/10 animate-pulse"></div>
        <div class="absolute top-4 left-4 text-6xl animate-bounce delay-100">🎉</div>
        <div class="absolute top-8 right-8 text-4xl animate-bounce delay-300">⭐</div>
        <div class="absolute bottom-4 left-8 text-5xl animate-bounce delay-500">🏆</div>
        <div class="absolute bottom-8 right-4 text-4xl animate-bounce delay-700">🌟</div>

        <div class="relative text-center">
          <!-- Success Icon -->
          <div
            class="mx-auto mb-6 flex items-center justify-center h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 shadow-lg animate-pulse">
            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>

          <!-- Main Message -->
          <h1 class="text-4xl md:text-5xl font-bold text-white mb-3 drop-shadow-lg">
            🎊 Félicitations ! 🎊
          </h1>

          <p class="text-xl md:text-2xl text-white/90 font-light">
            Formation terminée avec succès !
          </p>
        </div>
      </div>

      <!-- Content Section -->
      <div class="p-8 md:p-12">
        <!-- Formation Info Card -->
        @if(isset($formationWithProgress))
        <div
          class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 mb-8 border border-green-200 dark:border-green-800">
          <div class="flex items-center mb-4">
            <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center mr-4">
              <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $formationWithProgress->title }}
              </h2>
              <p class="text-sm text-gray-600 dark:text-gray-400">Formation complétée</p>
            </div>
          </div>

          @if($formationWithProgress->description)
          <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
            {{ $formationWithProgress->description }}
          </p>
          @endif
        </div>
        @endif

        <!-- Achievement Badge -->
        <div class="text-center mb-8">
          <div
            class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 shadow-lg mb-4 animate-pulse">
            <svg class="h-12 w-12 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
          </div>
          <p class="text-lg text-gray-600 dark:text-gray-400 font-medium">
            Certificat de réussite obtenu !
          </p>
        </div>

        <div class="mb-10">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Documents de fin de formation
          </h3>
          @if($formationWithProgress->completionDocuments->isNotEmpty())
          <ul class="space-y-3">
            @foreach($formationWithProgress->completionDocuments as $document)
            <li
              class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $document->title }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  {{ $document->original_name }}
                </p>
              </div>
              <a href="{{ route('eleve.formation.documents.download', [$team, $formationWithProgress, $document]) }}"
                class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200">
                Telecharger
              </a>
            </li>
            @endforeach
          </ul>
          @else
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Aucun document n'est disponible pour cette formation.
          </p>
          @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
          <a href="{{ route('eleve.index', $team) }}"
            class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <span class="relative z-10">Retour à l'accueil</span>
            <div
              class="absolute inset-0 rounded-xl bg-gradient-to-r from-blue-400 to-indigo-400 opacity-0 group-hover:opacity-20 transition-opacity duration-200">
            </div>
          </a>

          @if(isset($formationWithProgress))
          <a href="{{ route('eleve.formation.show', [$team, $formationWithProgress]) }}"
            class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <span class="relative z-10">Voir la formation</span>
            <div
              class="absolute inset-0 rounded-xl bg-gradient-to-r from-gray-500 to-gray-600 opacity-0 group-hover:opacity-20 transition-opacity duration-200">
            </div>
          </a>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Custom CSS for animations -->
<style>
  @keyframes float {

    0%,
    100% {
      transform: translateY(0px);
    }

    50% {
      transform: translateY(-10px);
    }
  }

  .animate-float {
    animation: float 3s ease-in-out infinite;
  }

  .delay-100 {
    animation-delay: 0.1s;
  }

  .delay-300 {
    animation-delay: 0.3s;
  }

  .delay-500 {
    animation-delay: 0.5s;
  }

  .delay-700 {
    animation-delay: 0.7s;
  }
</style>