<x-admin.layout
    :team="$team"
    :subtitle="__('Gérez les paramètres globaux de l\'application et les configurations spécifiques à l\'organisme.')"
>
    @include('in-application.admin.partials.configuration.index', ['team' => $team])
    @include('in-application.admin.partials.home-button', ['team' => $team])

</x-admin.layout>
