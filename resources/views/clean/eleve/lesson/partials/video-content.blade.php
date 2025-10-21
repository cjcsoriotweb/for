{{-- Contenu vidéo --}}

{{-- Composant Livewire pour la gestion vidéo --}}
@livewire('eleve.video-player', [ 'team' => $team, 'formation' => $formation,
'chapter' => $chapter, 'lesson' => $lesson, 'lessonContent' => $lessonContent ])
