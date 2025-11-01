@props([
    'action' => null,
    'method' => 'POST',
])

@php
    $formAction = $action ?? route('logout');
    $formMethod = strtoupper($method);
@endphp

<form method="{{ $formMethod }}" action="{{ $formAction }}" {{ $attributes }}>
    @csrf
    {{ $slot }}
</form>
