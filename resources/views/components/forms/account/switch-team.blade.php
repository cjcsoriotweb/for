@props([
    'team',
    'routeName' => 'user.switch',
    'identifierField' => 'team_id',
    'value' => null,
    'includeHiddenField' => true,
])

@php
    $target = route($routeName, $team);
    $fieldValue = $value ?? ($team->id ?? null);
@endphp

<form method="POST" action="{{ $target }}" {{ $attributes }}>
    @csrf

    @if($includeHiddenField && $identifierField && !is_null($fieldValue))
        <input type="hidden" name="{{ $identifierField }}" value="{{ $fieldValue }}">
    @endif

    {{ $slot }}
</form>
