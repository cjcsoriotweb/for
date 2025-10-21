<div>
    @if($autoplay)
    <a wire:click="autoplayOff">Desactiver AP</a>
    @else
    <a wire:click="autoplayOn">Activer AP</a>
    @endif

    <a
        href="{{ route('eleve.lesson.show', [
                        request()->route('team'),
                        $formation,
                        $currentLesson->chapter,
                        $currentLesson
                    ]) }}"
        class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
    >
        <svg
            class="w-6 h-6 mr-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 8a9 9 0 110-18 9 9 0 010 18z"
            ></path>
        </svg>
        Continuer: {{ $currentLesson->title }}
        <svg
            class="w-6 h-6 ml-3"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
            ></path>
        </svg>
    </a>
</div>
