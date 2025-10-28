<x-admin.layout
    :team="$team"
    icon="shield_person"
    :title="__('Tableau de bord administrateur')"
    :subtitle="__('Pilotez votre plateforme de formation avec une vue claire sur vos actions clés.')"
>
    <x-slot:headerActions>
        <a
            href="{{ route('application.admin.formations.index', $team) }}"
            class="inline-flex items-center rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-white transition-colors hover:bg-white/20"
        >
            <span class="material-symbols-outlined mr-2 text-base">library_books</span>
            {{ __('Formations') }}
        </a>
        <a
            href="{{ route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name]) }}"
            class="inline-flex items-center rounded-lg bg-emerald-500 px-4 py-2 text-white transition-colors hover:bg-emerald-600 shadow-sm"
        >
            <span class="material-symbols-outlined mr-2 text-base">tune</span>
            {{ __('Configuration') }}
        </a>
    </x-slot:headerActions>

    <div class="relative pb-16">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-900 opacity-80 blur-3xl"></div>
        <x-admin.admin-menu-fast :team="$team" />
    </div>
</x-admin.layout>
