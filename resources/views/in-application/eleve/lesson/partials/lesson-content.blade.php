{{-- Contenu texte et quiz --}}
@if($lessonType === 'text')
{{-- Contenu texte --}}
@include('in-application.eleve.lesson.partials.text-content') @elseif($lessonType ===
'quiz')
{{-- Contenu quiz --}}
@include('in-application.eleve.lesson.partials.quiz-content') @endif
