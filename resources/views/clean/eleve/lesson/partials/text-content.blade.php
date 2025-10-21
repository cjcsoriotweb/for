{{-- Contenu texte --}}
<div class="prose dark:prose-invert max-w-none mb-6">
    {!! nl2br(e($lessonContent->content)) !!}
</div>

{{-- Barre de progression de lecture --}}
@if($lessonProgress && $lessonProgress->pivot->read_percent !== null)
<div class="mb-6">
    <div class="flex justify-between text-sm text-gray-600 mb-2">
        <span>Progression de lecture</span>
        <span>{{ $lessonProgress->pivot->read_percent }}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div
            class="bg-blue-600 h-2 rounded-full"
            style="width: {{ $lessonProgress->pivot->read_percent }}%"
        ></div>
    </div>
</div>
@endif

{{-- Actions texte --}}
@include('clean.eleve.lesson.partials.lesson-actions')
