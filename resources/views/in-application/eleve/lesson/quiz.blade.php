<x-eleve-layout :team="$team">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <livewire:eleve.quiz-component
            :team="$team"
            :formation="$formation"
            :chapter="$chapter"
            :lesson="$lesson"
        />

        @if($lessonResources->isNotEmpty())
            @include('in-application.eleve.lesson.partials.lesson-resources', [
                'lessonResources' => $lessonResources,
                'canDownloadLessonResources' => $canDownloadLessonResources,
            ])
        @endif
    </div>
</x-eleve-layout>
