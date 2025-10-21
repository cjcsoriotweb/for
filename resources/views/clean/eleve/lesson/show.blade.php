<x-eleve-layout :team="$team">
    {{-- Messages de notification --}}
    @include('clean.eleve.lesson.partials.notifications')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Navigation fil d'Ariane --}}
        @include('clean.eleve.lesson.partials.breadcrumb')

        {{-- En-tête de la leçon --}}
        @include('clean.eleve.lesson.partials.header')

        {{-- Contenu principal --}}
        @include('clean.eleve.lesson.partials.main-content')

        {{-- Navigation de la leçon --}}
        @include('clean.eleve.lesson.partials.navigation')
    </div>
</x-eleve-layout>
