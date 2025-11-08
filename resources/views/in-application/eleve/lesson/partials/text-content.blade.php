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



{{-- Indicateur de progression de lecture --}}
@if($lessonProgress && $lessonProgress->pivot->read_percent !== null)
    @livewire('eleve.formation.progress-display', ['lesson' => $lesson], key('progress-display-' . $lesson->id))
@endif
