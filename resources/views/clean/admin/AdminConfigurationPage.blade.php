<x-admin.layout
    :team="$team"
    :subtitle="__('Gérez les paramètres globaux de l\'application et les configurations spécifiques à l\'organisme.')"
>
    @include('clean.admin.partials.configuration.index', ['team' => $team])
    <br />
    @include('clean.admin.partials.home-button', ['team' => $team])
    <br />
    @include('clean.admin.partials.configuration.add-credit', ['team' => $team])
</x-admin.layout>
