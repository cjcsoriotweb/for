<div
    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
>
    @livewire('teams.update-team-name-form', ['team' => $team])
</div>

<div
    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
>
    @include('teams.partials.update-team-photo-form', ['team' => $team])
</div>
