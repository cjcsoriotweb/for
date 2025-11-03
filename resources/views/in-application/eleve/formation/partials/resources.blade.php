@php
    use Illuminate\Support\Facades\Storage;

    $hasFormationDocs = isset($formationDocuments) && $formationDocuments->isNotEmpty();
    $hasLessonResources = isset($lessonResources) && $lessonResources->isNotEmpty();
@endphp

@if($hasFormationDocs || $hasLessonResources)
<section class="mt-10">
  <div class="bg-gradient-to-br from-white to-gray-50/50 dark:from-gray-900 dark:to-gray-900/50 sm:rounded-3xl overflow-hidden shadow-xl border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm">
    <!-- Header Section -->
    <div class="px-8 py-6 border-b border-gray-200/60 dark:border-gray-700/60 bg-gradient-to-r from-indigo-50/30 to-purple-50/30 dark:from-indigo-900/10 dark:to-purple-900/10">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
          Ressources de formation
        </h2>
      </div>
      <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
        Retrouvez ici tous les documents officiels ainsi que les fichiers ajoutés aux leçons textes.
      </p>
    </div>

    <div class="px-8 py-8 space-y-8">
      @if($hasFormationDocs)
      <!-- Official Documents Section -->
      <details class="group bg-gradient-to-br from-amber-50/80 to-orange-50/80 dark:from-amber-900/20 dark:to-orange-900/20 border-2 border-amber-200/60 dark:border-amber-700/40 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
        <summary class="flex items-center justify-between px-6 py-5 cursor-pointer text-gray-900 dark:text-gray-100 font-semibold hover:bg-amber-100/50 dark:hover:bg-amber-900/30 rounded-t-2xl transition-colors duration-200">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-md">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <span class="text-lg">Documents officiels de la formation</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-white/60 dark:bg-gray-800/60 px-3 py-1 rounded-full">
              {{ $isFormationCompleted ? 'Cliquer pour afficher' : 'Formation incomplète' }}
            </span>
            <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </summary>

        <div class="px-6 pb-6">
          @if(! $isFormationCompleted)
          <div class="bg-gradient-to-r from-red-400 to-red-500 text-white rounded-xl px-6 py-4 shadow-lg border border-yellow-300/50">
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
              </svg>
              <div>
                <p class="font-semibold">Formation non terminée</p>
                <p class="text-yellow-100 text-sm">Terminez la formation pour débloquer ces documents finaux.</p>
              </div>
            </div>
          </div>
          @else
          <div class="space-y-3">
            @foreach($formationDocuments as $document)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md border border-gray-200/50 dark:border-gray-700/50 hover:shadow-lg transition-all duration-200">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                  <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md flex-shrink-0">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-gray-900 dark:text-gray-100 font-semibold break-words">
                        {{ $document->title ?? $document->original_name }}
                      </p>
                      @if($document->description)
                      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $document->description }}
                      </p>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="sm:flex-shrink-0">
                  <a
                    href="{{ route('eleve.formation.documents.download', [$team, $formation, $document]) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </details>
      @endif

      @if($hasLessonResources)
      @php
        $resourcesByChapter = $lessonResources->groupBy(fn ($resource) => $resource['chapter_title'] ?? 'Autres leçons');
      @endphp

      <!-- Lesson Resources Section -->
      <details class="group bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200/60 dark:border-blue-700/40 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300" open>
        <summary class="flex items-center justify-between px-6 py-5 cursor-pointer text-gray-900 dark:text-gray-100 font-semibold hover:bg-blue-100/50 dark:hover:bg-blue-900/30 rounded-t-2xl transition-colors duration-200">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <span class="text-lg">Ressources liées aux leçons</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 dark:text-gray-400 bg-white/60 dark:bg-gray-800/60 px-3 py-1 rounded-full">
              {{ $lessonResources->count() }} leçon{{ $lessonResources->count() > 1 ? 's' : '' }}
            </span>
            <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </summary>

        <div class="px-6 pb-6 space-y-8">
          @foreach($resourcesByChapter as $chapterTitle => $resources)
          <div class="space-y-4">
            <div class="flex items-center gap-2">
              <div class="h-px bg-gradient-to-r from-blue-200 to-indigo-200 dark:from-blue-700 dark:to-indigo-700 flex-1"></div>
              <h3 class="text-sm font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider bg-white dark:bg-gray-800 px-3 py-1 rounded-full shadow-sm">
                {{ $chapterTitle }}
              </h3>
              <div class="h-px bg-gradient-to-r from-indigo-200 to-purple-200 dark:from-indigo-700 dark:to-purple-700 flex-1"></div>
            </div>

            <div class="space-y-4">
              @foreach($resources as $resource)
              @php
                $attachments = $resource['attachments'];
                $isLessonCompleted = $resource['is_completed'];
              @endphp
              <div class="border-2 {{ $isLessonCompleted ? 'border-green-200/60 dark:border-green-700/40 bg-gradient-to-br from-green-50/50 to-emerald-50/50 dark:from-green-900/10 dark:to-emerald-900/10' : 'border-gray-200/60 dark:border-gray-700/40 bg-gradient-to-br from-gray-50/50 to-slate-50/50 dark:from-gray-800/50 dark:to-slate-800/50' }} rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                  <div class="flex-1">
                    <div class="flex items-start gap-3">
                      <div class="w-12 h-12 rounded-xl {{ $isLessonCompleted ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-gray-400 to-slate-500' }} flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isLessonCompleted ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' }}"/>
                        </svg>
                      </div>
                      <div>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                          {{ $resource['lesson_title'] }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                          {{ $attachments->count() }} ressource{{ $attachments->count() > 1 ? 's' : '' }} attachée{{ $attachments->count() > 1 ? 's' : '' }}
                        </p>
                      </div>
                    </div>
                  </div>
                  @if(! $isLessonCompleted)
                  <div class="lg:flex-shrink-0">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-gradient-to-r from-gray-400 to-slate-500 text-white shadow-lg">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                      Terminer la leçon pour débloquer
                    </span>
                  </div>
                  @endif
                </div>

                <div class="mt-6 space-y-3">
                  @foreach($attachments as $attachment)
                  @php
                    $downloadUrl = Storage::disk('public')->url($attachment->file_path);
                    $isInline = $attachment->display_mode === 'inline';
                  @endphp
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white/60 dark:bg-gray-800/60 rounded-xl px-4 py-3 border border-gray-200/50 dark:border-gray-700/50 hover:bg-white dark:hover:bg-gray-800 transition-colors duration-200">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded-lg {{ $isInline ? 'bg-gradient-to-br from-red-500 to-pink-600' : 'bg-gradient-to-br from-blue-500 to-indigo-600' }} flex items-center justify-center shadow-md">
                        @if($isInline)
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @endif
                      </div>
                      <div>
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 break-words">
                          {{ $attachment->name }}
                        </p>
                        @if($isInline)
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                          Affiché dans la leçon
                        </p>
                        @endif
                      </div>
                    </div>
                    <div class="sm:flex-shrink-0">
                      @if($isLessonCompleted)
                      <a
                        href="{{ $downloadUrl }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl {{ $isInline ? 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white' : 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white' }} transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        @if($isInline)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Afficher
                        @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger
                        @endif
                      </a>
                      @else
                      <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-gray-400 to-slate-500 text-white cursor-not-allowed shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Accès verrouillé
                      </span>
                      @endif
                    </div>
                  </div>
                  @endforeach
                </div>
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
