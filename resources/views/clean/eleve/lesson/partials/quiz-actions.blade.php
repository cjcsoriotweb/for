{{-- Actions du Quiz --}}
@if($lessonProgress && $lessonProgress->pivot->attempts >=
$lessonContent->max_attempts && $lessonContent->max_attempts > 0)
{{-- Quiz bloqué - pas d'action disponible --}}
@else
<div class="text-center">
    <a
        href="{{
            route('eleve.lesson.quiz.attempt', [
                $team,
                $formation,
                $chapter,
                $lesson
            ])
        }}"
        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-block"
    >
        Commencer le Quiz
    </a>
</div>
@endif
