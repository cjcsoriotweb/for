{{-- Contenu texte --}}


@livewire('eleve.formation.readtext', ['requiredTime' =>
$lessonContent->estimated_read_time, 'lesson' => $lesson])
<div class="prose dark:prose-invert max-w-none mb-6">
    {!! $lessonContent->content !!}
</div>

{{-- Actions texte --}}
