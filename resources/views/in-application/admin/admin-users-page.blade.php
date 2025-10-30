<x-admin.layout :team="$team">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @livewire('teams.team-member-manager', ['team' => $team])
    </div>
</x-admin.layout>
