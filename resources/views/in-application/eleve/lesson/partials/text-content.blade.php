{{-- Contenu texte avec design amélioré --}}
@livewire('eleve.formation.readtext', ['requiredTime' => $lessonContent->estimated_read_time, 'team' => $team, 'formation' => $formation, 'lesson' => $lesson])

@php
    $attachments = $lessonContent->attachments ?? collect();
    $inlineAttachment = $attachments->firstWhere('display_mode', 'inline');
    $downloadAttachments = $attachments->where('display_mode', 'download');
    $isLessonCompleted = optional(optional($lessonProgress ?? null)->pivot)->status === 'completed';
    $hasStartedFormation = true; // L'étudiant a accès aux documents dès qu'il peut voir la leçon (donc il est inscrit)
@endphp

{{-- Contenu principal avec meilleure typographie --}}
<div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 mb-8 transition-all duration-300 hover:shadow-xl">
    <div class="prose prose-lg dark:prose-invert max-w-none">
        <div class="text-gray-800 dark:text-gray-200 leading-relaxed space-y-4">
            {!! nl2br(e($lessonContent->content)) !!}
        </div>
    </div>
</div>

{{-- Section ressources avec design moderne --}}
@if($attachments->isNotEmpty())
    @if($hasStartedFormation)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8 transition-all duration-300 hover:shadow-xl">
            {{-- Header de la section ressources --}}
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 rounded-full p-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Ressources complémentaires</h3>
                    </div>
                    <div class="text-white text-sm opacity-90">
                        {{ $attachments->count() }} ressource{{ $attachments->count() > 1 ? 's' : '' }}
                    </div>
                </div>
            </div>

            {{-- Contenu des ressources --}}
            <div class="p-6 space-y-6">
                @if($inlineAttachment)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-2">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Document principal</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $inlineAttachment->name }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                {{-- Bouton pour afficher/masquer le PDF --}}
                                <button
                                    onclick="togglePdfViewer(this)"
                                    class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="pdf-toggle-text">Afficher</span>
                                </button>
                                <a
                                    href="{{ Storage::disk('public')->url($inlineAttachment->file_path) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors duration-200"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Ouvrir
                                </a>
                            </div>
                        </div>

                        {{-- Conteneur du PDF (masqué par défaut) --}}
                        <div id="pdf-viewer-{{ $inlineAttachment->id }}" class="pdf-viewer-container hidden">
                            <div class="relative border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                                <iframe
                                    src="{{ Storage::disk('public')->url($inlineAttachment->file_path) }}"
                                    title="Document de la leçon"
                                    class="w-full h-[600px]"
                                ></iframe>
                                <div class="absolute top-2 right-2">
                                    <button
                                        onclick="closePdfViewer({{ $inlineAttachment->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white rounded-full p-2 transition-colors duration-200"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Script JavaScript pour gérer l'affichage du PDF --}}
                    <script>
                        function togglePdfViewer(button) {
                            console.log('Toggle PDF clicked');
                            const attachmentId = {{ $inlineAttachment->id }};
                            const container = document.getElementById('pdf-viewer-' + attachmentId);
                            const toggleText = button.querySelector('.pdf-toggle-text');

                            console.log('Container found:', container);
                            console.log('Toggle text found:', toggleText);

                            if (container && toggleText) {
                                if (container.classList.contains('hidden')) {
                                    container.classList.remove('hidden');
                                    toggleText.textContent = 'Masquer';
                                    button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                                    button.classList.add('bg-orange-600', 'hover:bg-orange-700');
                                    console.log('PDF shown');
                                } else {
                                    container.classList.add('hidden');
                                    toggleText.textContent = 'Afficher';
                                    button.classList.remove('bg-orange-600', 'hover:bg-orange-700');
                                    button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                    console.log('PDF hidden');
                                }
                            } else {
                                console.error('Container or toggle text not found');
                            }
                        }

                        function closePdfViewer(attachmentId) {
                            console.log('Close PDF clicked for attachment:', attachmentId);
                            const container = document.getElementById('pdf-viewer-' + attachmentId);
                            const toggleButton = document.querySelector('button[onclick="togglePdfViewer(this)"]');
                            const toggleText = toggleButton ? toggleButton.querySelector('.pdf-toggle-text') : null;

                            if (container && toggleButton && toggleText) {
                                container.classList.add('hidden');
                                toggleText.textContent = 'Afficher';
                                toggleButton.classList.remove('bg-orange-600', 'hover:bg-orange-700');
                                toggleButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                                console.log('PDF closed');
                            } else {
                                console.error('Elements not found for closing PDF');
                            }
                        }
                    </script>
                @endif

                @if($downloadAttachments->isNotEmpty())
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-green-100 dark:bg-green-900 rounded-full p-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Fichiers à télécharger</h4>
                        </div>
                        <div class="grid gap-3">
                            @foreach($downloadAttachments as $attachment)
                                <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors duration-200">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <div class="bg-gray-100 dark:bg-gray-600 rounded-full p-2">
                                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $attachment->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Cliquez pour télécharger</p>
                                        </div>
                                    </div>
                                    <a
                                        href="{{ Storage::disk('public')->url($attachment->file_path) }}"
                                        target="_blank"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 ml-4"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Télécharger
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 rounded-xl px-6 py-4 mb-8">
            <div class="flex items-center space-x-3">
                <div class="bg-yellow-100 dark:bg-yellow-800 rounded-full p-2">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold">Accès limité</h4>
                    <p class="text-sm">Inscrivez-vous à la formation pour accéder aux documents complémentaires.</p>
                </div>
            </div>
        </div>
    @endif
@endif

{{-- Indicateur de progression de lecture --}}
@if($lessonProgress && $lessonProgress->pivot->read_percent !== null)
    @livewire('eleve.formation.progress-display', ['lesson' => $lesson], key('progress-display-' . $lesson->id))
@endif
