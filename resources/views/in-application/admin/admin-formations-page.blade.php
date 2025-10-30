<x-admin.layout :team="$team">
    <x-admin.admin-formations :team="$team" />
    @include('in-application.admin.partials.home-button', ['team' => $team])
</x-admin.layout>
