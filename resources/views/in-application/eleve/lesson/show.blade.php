<x-eleve-layout :team="$team">
    {{-- Messages de notification --}}
    @include('in-application.eleve.lesson.partials.notifications')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Navigation fil d'Ariane --}}
        @include('in-application.eleve.lesson.partials.breadcrumb')

        {{-- En-tête de la leçon --}}
        @include('in-application.eleve.lesson.partials.header')

        {{-- Contenu principal --}}
        @include('in-application.eleve.lesson.partials.main-content')

        {{-- Navigation de la leçon --}}
        @include('in-application.eleve.lesson.partials.navigation')
    </div>

    @auth
        @php
            $chatTrainerSlug = $assistantTrainerSlug ?? config('ai.default_trainer_slug', 'default');
            $chatTrainerTitle = $assistantTrainerName ?? __('Assistant Formation');
        @endphp

        @if (! empty($chatTrainerSlug))
            @push('chatboxes')
                <livewire:chat-box :trainer="$chatTrainerSlug" :title="$chatTrainerTitle" />
            @endpush
        @endif
    @endauth
</x-eleve-layout>
