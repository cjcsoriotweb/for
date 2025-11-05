<x-eleve-layout :team="$team">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-eleve.notification-messages />

    <!-- Header avec célébration -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-8 mb-8 text-white">
      <div class="flex items-center gap-4 mb-4">
        <div class="flex-shrink-0">
          <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold">Formation terminée !</h1>
          <p class="text-green-100 mt-1">Félicitations pour votre réussite</p>
        </div>
      </div>

      <div class="bg-white/10 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-semibold">{{ $formationWithProgress->title }}</h2>
            <p class="text-green-100 mt-1">{{ $formationWithProgress->description }}</p>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold">{{ $progress['progress_percent'] ?? 100 }}%</div>
            <div class="text-sm text-green-100">Complété</div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Documents de certification -->
      <div class="lg:col-span-2 space-y-6">
        @if($formationDocuments->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex items-center gap-3 mb-6">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h4a2 2 0 012 2v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2z" clip-rule="evenodd" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Vos documents de certification</h3>
          </div>

          <div class="space-y-4">
            @foreach($formationDocuments as $document)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-600">
              <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                  <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $document->title ?? $document->original_name }}
                  </p>
                  @if($document->description)
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ $document->description }}
                  </p>
                  @endif
                </div>
              </div>
              <a href="{{ route('eleve.formation.documents.download', [$team, $formationWithProgress, $document]) }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                Télécharger
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <!-- Aperçu des chapitres et leçons -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <div class="flex items-center gap-3 mb-6">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Votre parcours de formation</h3>
          </div>

          <div class="space-y-6">
            @foreach($chaptersWithLessons as $chapter)
            <div class="border {{ $chapter['completed_count'] === $chapter['total_count'] ? 'border-green-300 dark:border-green-600 bg-green-50/50 dark:bg-green-900/10' : 'border-gray-200 dark:border-gray-600' }} rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                  <h4 class="text-md font-semibold {{ $chapter['completed_count'] === $chapter['total_count'] ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100' }}">{{ $chapter['title'] }}</h4>
                  @if($chapter['completed_count'] === $chapter['total_count'])
                  <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 dark:bg-green-800 dark:text-green-200 rounded-full">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Chapitre terminé
                  </span>
                  @endif
                </div>
                <span class="text-sm {{ $chapter['completed_count'] === $chapter['total_count'] ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                  {{ $chapter['completed_count'] }}/{{ $chapter['total_count'] }} leçons terminées
                </span>
              </div>

              <div class="space-y-2">
                @foreach($chapter['lessons'] as $lesson)
                <div class="flex items-center gap-3 p-2 rounded-md {{ $lesson['is_completed'] ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-900/20' }}">
                  <div class="flex-shrink-0">
                    @if($lesson['is_completed'])
                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    @else
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    @endif
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                      {{ $lesson['lesson_title'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      @if($lesson['lesson_type'] === 'App\Models\VideoContent')
                        Vidéo
                      @elseif($lesson['lesson_type'] === 'App\Models\TextContent')
                        Contenu texte
                      @elseif($lesson['lesson_type'] === 'App\Models\Quiz')
                        Quiz
                      @else
                        Contenu
                      @endif
                      @if($lesson['is_completed'] && $lesson['completed_at'])
                        • Terminé le {{ \Carbon\Carbon::parse($lesson['completed_at'])->format('d/m/Y') }}
                      @endif
                    </p>
                  </div>
                  @if($lesson['attachments']->count() > 0)
                  <div class="flex-shrink-0">
                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  @endif
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Sidebar avec statistiques -->
      <div class="space-y-6">
        <!-- Statistiques de progression -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Vos statistiques</h3>

          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600 dark:text-gray-400">Progression globale</span>
              <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $progress['progress_percent'] ?? 100 }}%</span>
            </div>

            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
              <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progress['progress_percent'] ?? 100 }}%"></div>
            </div>

            <div class="pt-2 border-t border-gray-200 dark:border-gray-600">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Date d'inscription</span>
                <span class="font-medium text-gray-900 dark:text-gray-100">
                  {{ $progress['enrolled_at'] ? \Carbon\Carbon::parse($progress['enrolled_at'])->format('d/m/Y') : 'N/A' }}
                </span>
              </div>

              <div class="flex items-center justify-between text-sm mt-2">
                <span class="text-gray-600 dark:text-gray-400">Date de completion</span>
                <span class="font-medium text-gray-900 dark:text-gray-100">
                  {{ $progress['completed_at'] ? \Carbon\Carbon::parse($progress['completed_at'])->format('d/m/Y') : 'N/A' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions disponibles -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions disponibles</h3>

          <div class="space-y-3">
            <a href="{{ route('eleve.formation.show', [$team, $formationWithProgress]) }}"
              class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
              </svg>
              Revoir la formation
            </a>

            <a href="{{ route('eleve.index', $team) }}"
              class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3.101a7.002 7.002 0 01-11.601-2.566 1 1 0 111.885-.666A5.002 5.002 0 005.999 7H9V2a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Retour à l'accueil
            </a>
          </div>
        </div>

        <!-- Assistant IA -->
        @if($assistantTrainer)
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 9a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div>
              <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $assistantTrainerName }}</h4>
              <p class="text-xs text-gray-600 dark:text-gray-400">Votre assistant formation</p>
            </div>
          </div>

          <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
            Besoin d'aide ou de conseils supplémentaires ? Je suis là pour vous accompagner dans votre apprentissage.
          </p>

          <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
            </svg>
            Discuter avec l'assistant
          </button>
        </div>
        @endif
      </div>
    </div>
  </div>
</x-eleve-layout>
