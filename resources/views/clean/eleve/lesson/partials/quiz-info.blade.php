{{-- Informations du Quiz --}}
<div
    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-4"
>
    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">
        Informations du Quiz
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        @if($lessonContent->max_attempts > 0)
        <div>
            <span class="font-medium">Tentatives max:</span>
            {{ $lessonContent->max_attempts }}
        </div>
        @endif
        <div>
            <span class="font-medium">Questions:</span>
            {{ $lessonContent->quizQuestions->count() }}
        </div>
    </div>
</div>

{{-- Message d'erreur si tentatives épuisées --}}
@if($lessonProgress && $lessonProgress->pivot->attempts >=
$lessonContent->max_attempts && $lessonContent->max_attempts > 0)
<div
    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4"
>
    <p class="text-red-800 dark:text-red-200">
        Vous avez atteint le nombre maximum de tentatives pour ce quiz.
    </p>
</div>
@endif
