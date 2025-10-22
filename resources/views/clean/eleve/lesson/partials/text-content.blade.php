{{-- Contenu texte --}}


@livewire('eleve.formation.readtext', ['requiredTime' =>
$lessonContent->estimated_read_time, 'team'=> $team, 'formation'=>$formation, 'lesson' => $lesson])
<div class="prose dark:prose-invert max-w-none mb-6">
  {!! nl2br(e($lessonContent->content)) !!}
</div>

{{-- Actions texte --}}