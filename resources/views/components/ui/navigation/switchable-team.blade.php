@props(['team', 'component' => 'dropdown-link'])

<x-forms.account.set-current-team :team="$team" :component="$component" x-data>
    <div class="flex items-center">
        @if (Auth::user()->isCurrentTeam($team))
        <svg
            class="me-2 size-5 text-green-400"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 0 0118 0z"
            />
        </svg>
        @endif

        <div class="truncate">{{ $team->name }}</div>
    </div>
</x-forms.account.set-current-team>
