@php
    use Illuminate\Support\Facades\Storage;

    $hasFormationDocs = isset($formationDocuments) && $formationDocuments->isNotEmpty();
    $hasLessonResources = isset($lessonResources) && $lessonResources->isNotEmpty();
@endphp

@if($hasFormationDocs || $hasLessonResources)
<section class="mt-10">
  <div
    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm sm:rounded-2xl overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
        Ressources de la formation
      </h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Retrouvez ici tous les documents officiels ainsi que les fichiers ajoutés aux leçons textes.
      </p>
    </div>

    <div class="px-6 py-6 space-y-8">
      @if($hasFormationDocs)
      <details class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl">
        <summary
          class="flex items-center justify-between px-5 py-4 cursor-pointer text-gray-900 dark:text-gray-100 font-medium">
          <span>Documents officiels de la formation</span>
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $isFormationCompleted ? 'Cliquer pour afficher' : 'Compléter la formation' }}
          </span>
        </summary>

        <div class="px-5 pb-5 space-y-4">
          @if(! $isFormationCompleted)
          <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg px-4 py-3">
            Terminez la formation pour débloquer ces documents finaux.
          </div>
          @else
          <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($formationDocuments as $document)
            <li class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div class="flex-1 pr-0 sm:pr-4">
                <p class="text-gray-900 dark:text-gray-100 font-medium break-words">
                  {{ $document->title ?? $document->original_name }}
                </p>
                @if($document->description)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  {{ $document->description }}
                </p>
                @endif
              </div>
              <div class="mt-3 sm:mt-0">
                <a
                  href="{{ route('eleve.formation.documents.download', [$team, $formation, $document]) }}"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
                  Télécharger
                </a>
              </div>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
      </details>
      @endif

      @if($hasLessonResources)
      @php
        $resourcesByChapter = $lessonResources->groupBy(fn ($resource) => $resource['chapter_title'] ?? 'Autres leçons');
      @endphp

      <details class="bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl" open>
        <summary
          class="flex items-center justify-between px-5 py-4 cursor-pointer text-gray-900 dark:text-gray-100 font-medium">
          <span>Ressources liées aux leçons</span>
          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $lessonResources->count() }} leçon{{ $lessonResources->count() > 1 ? 's' : '' }}
          </span>
        </summary>

        <div class="px-5 pb-5 space-y-6">
          @foreach($resourcesByChapter as $chapterTitle => $resources)
          <div class="space-y-3">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
              {{ $chapterTitle }}
            </h3>

            <div class="space-y-4">
              @foreach($resources as $resource)
              @php
                $attachments = $resource['attachments'];
                $isLessonCompleted = $resource['is_completed'];
              @endphp
              <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                  <div>
                    <p class="text-base font-medium text-gray-900 dark:text-gray-100">
                      {{ $resource['lesson_title'] }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                      {{ $attachments->count() }} ressource{{ $attachments->count() > 1 ? 's' : '' }} attachée{{ $attachments->count() > 1 ? 's' : '' }}
                    </p>
                  </div>
                  @if(! $isLessonCompleted)
                  <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                    Terminer la leçon pour débloquer
                  </span>
                  @endif
                </div>

                <ul class="mt-4 space-y-2">
                  @foreach($attachments as $attachment)
                  @php
                    $downloadUrl = Storage::disk('public')->url($attachment->file_path);
                    $isInline = $attachment->display_mode === 'inline';
                  @endphp
                  <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-gray-50 dark:bg-gray-900/60 rounded-md px-3 py-2">
                    <div class="flex items-start gap-3">
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-xs font-semibold uppercase">
                        {{ $isInline ? 'PDF' : 'DOC' }}
                      </span>
                      <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 break-words">
                          {{ $attachment->name }}
                        </p>
                        @if($isInline)
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                          Affiché dans la leçon
                        </p>
                        @endif
                      </div>
                    </div>
                    <div>
                      @if($isLessonCompleted)
                      <a
                        href="{{ $downloadUrl }}"
                        target="_blank"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md {{ $isInline ? 'bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/60 dark:text-blue-200 dark:hover:bg-blue-900' : 'bg-indigo-600 text-white hover:bg-indigo-700' }} transition-colors">
                        {{ $isInline ? 'Afficher' : 'Télécharger' }}
                      </a>
                      @else
                      <span
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-300 cursor-not-allowed">
                        Accès verrouillé
                      </span>
                      @endif
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </details>
      @endif
    </div>
  </div>
</section>
@endif
