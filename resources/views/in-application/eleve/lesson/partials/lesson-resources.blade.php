{{-- Ressources liees a la lecon --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Ressources de la lecon
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Documents partages pour {{ $lesson->getName() }}
        </p>
    </div>

    <div>
        @foreach($lessonResources as $resource)
            @php
                $extension = strtoupper(pathinfo($resource->file_path ?? '', PATHINFO_EXTENSION) ?: 'DOC');
            @endphp
            <div class="px-6 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 last:border-b-0 dark:border-gray-700">
                <div class="flex items-start gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                        {{ $extension }}
                    </span>
                    <div>
                        <p class="text-gray-900 dark:text-gray-100 font-medium break-words">
                            {{ $resource->name ?? __('Piece jointe') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $resource->mime_type ?? 'Document' }}
                        </p>
                    </div>
                </div>
                <div>
                    @if($canDownloadLessonResources)
                        <a
                            href="{{ route('eleve.lesson.resources.download', [$team, $formation, $chapter, $lesson, $resource]) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors"
                        >
                            Telecharger
                        </a>
                    @else
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Commencez ou terminez la lecon pour debloquer le telechargement.
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
