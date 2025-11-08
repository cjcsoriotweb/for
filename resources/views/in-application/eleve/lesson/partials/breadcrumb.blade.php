{{-- Navigation fil d'Ariane --}}
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a
                href="{{ route('eleve.index', $team) }}"
                class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
            >
                Accueil
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg
                    class="w-3 h-3 text-gray-400 mx-1"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 6 10"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 9 4-4-4-4"
                    />
                </svg>
                <a
                    href="{{
                        route('eleve.formation.show', [$team, $formation])
                    }}"
                    class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                >
                    {{ $formation->title }}
                </a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg
                    class="w-3 h-3 text-gray-400 mx-1"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 6 10"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 9 4-4-4-4"
                    />
                </svg>
                <span
                    class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400"
                    >{{ $chapter->title }}</span
                >
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg
                    class="w-3 h-3 text-gray-400 mx-1"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 6 10"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m1 9 4-4-4-4"
                    />
                </svg>
                <span
                    class="ml-1 text-blue-600 md:ml-2 dark:text-blue-400"
                    >{{ $lesson->getName() }}</span
                >
            </div>
        </li>
    </ol>
</nav>
