{{-- Contenu texte --}}
<div class="prose dark:prose-invert max-w-none mb-6">
    {!! nl2br(e($lessonContent->content)) !!}
</div>

@livewire('eleve.formation.readtext', ['requiredTime' =>
$lessonContent->estimated_read_time, 'lesson' => $lesson])

{{$lessonContent->estimated_read_time}}

{{-- Actions texte --}}
