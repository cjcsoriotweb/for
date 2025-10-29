{{-- Contenu texte et quiz --}}
@if($lessonType === 'text')
{{-- Contenu texte --}}
@include('clean.eleve.lesson.partials.text-content') @elseif($lessonType ===
'quiz')
{{-- Contenu quiz --}}
@include('clean.eleve.lesson.partials.quiz-content') @endif
