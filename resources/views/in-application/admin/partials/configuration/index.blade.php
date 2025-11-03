<div
    class="p-6"
>
    @livewire('teams.update-team-name-form', ['team' => $team])
</div>

<div
    class="p-6"
>
    @include('teams.partials.update-team-photo-form', ['team' => $team])
</div>
