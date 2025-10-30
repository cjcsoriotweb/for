@props(['team', 'component' => 'dropdown-link'])

<form method="POST" action="{{ route('current-team.update') }}" {{ $attributes }}>
    @csrf

    <input type="hidden" name="team_id" value="{{ $team->id }}" />

    <x-dynamic-component
        :component="$component"
        href="#"
        x-on:click.prevent="$root.submit();"
    >
        {{ $slot }}
    </x-dynamic-component>
</form>
