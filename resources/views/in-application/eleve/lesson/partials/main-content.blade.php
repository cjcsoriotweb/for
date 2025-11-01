{{-- Contenu principal de la leçon --}}
<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        {{-- Afficher le contenu selon le type de leçon --}}
        @if($lessonType === 'video')
        @include('in-application.eleve.lesson.partials.video-content')
        @else
        @include('in-application.eleve.lesson.partials.lesson-content')
        @endif
    </div>
</div>

@if(isset($formationDocuments) && $formationDocuments->isNotEmpty())
    @include('in-application.eleve.lesson.partials.formation-documents', [
        'formationDocuments' => $formationDocuments,
        'isFormationCompleted' => $isFormationCompleted ?? false
    ])
@endif
