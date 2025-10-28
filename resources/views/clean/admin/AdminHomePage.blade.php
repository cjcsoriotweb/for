@php($headerIcon = 'shield_person')

<x-application-layout :team="$team">
    <x-slot name="header">
        {{ __('Tableau de bord administrateur') }}
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Pilotez votre plateforme de formation avec une vue claire sur vos actions clés.') }}
    </x-slot>

    <x-slot name="headerActions">
        <div class="flex items-center space-x-3">
            <a
                href="{{ route('application.admin.formations.index', $team) }}"
                class="inline-flex items-center px-4 py-2 rounded-lg border border-white/40 text-white bg-white/10 hover:bg-white/20 transition-colors"
            >
                <span class="material-symbols-outlined text-base mr-2">library_books</span>
                {{ __('Formations') }}
            </a>
            <a
                href="{{ route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name]) }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition-colors shadow-sm"
            >
                <span class="material-symbols-outlined text-base mr-2">tune</span>
                {{ __('Configuration') }}
            </a>
        </div>
    </x-slot>

    <div class="relative pb-16">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-900 opacity-80 blur-3xl"></div>
        <x-admin.admin-menu-fast :team="$team" />
    </div>
</x-application-layout>
