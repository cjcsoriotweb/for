<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
  <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-10 text-center text-white">
      <div class="text-sm uppercase tracking-widest font-semibold mb-3">Formation terminee</div>
      <h1 class="text-3xl sm:text-4xl font-bold mb-2">Felicitations !</h1>
      <p class="text-base sm:text-lg text-white/90">
        Vous avez reussi la formation {{ $formationWithProgress->title }}.
      </p>
    </div>

    <div class="px-6 py-8 space-y-8">
      <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl px-5 py-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Votre parcours</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
          Bravo pour votre engagement. Vous pouvez maintenant telecharger votre certificat et consulter l'ensemble des
          ressources liees aux lecons suivies.
        </p>
        @if(! empty($formationWithProgress->description))
        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
          {{ $formationWithProgress->description }}
        </p>
        @endif
      </div>

      @if(isset($formationDocuments) && $formationDocuments->isNotEmpty())
      <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-5 py-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Documents officiels</h3>
        <ul class="space-y-3">
          @foreach($formationDocuments as $document)
          <li
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg px-4 py-3">
            <div>
              <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                {{ $document->title ?? $document->original_name }}
              </p>
              @if($document->description)
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $document->description }}
              </p>
              @endif
            </div>
            <a href="{{ route('eleve.formation.documents.download', [$team, $formationWithProgress, $document]) }}"
              class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
              Telecharger
            </a>
          </li>
          @endforeach
        </ul>
      </div>
      @endif

      @include('clean.eleve.formation.partials.resources', [
      'formationDocuments' => collect(),
      'lessonResources' => $lessonResources ?? collect(),
      'isFormationCompleted' => true,
      'team' => $team,
      'formation' => $formationWithProgress,
      ])

      <div class="flex flex-col sm:flex-row gap-3 justify-center pt-4">
        <a href="{{ route('eleve.index', $team) }}"
          class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
          Retour a l'accueil
        </a>
      </div>
    </div>
  </div>
</div>