@props(['action' => null])

@php
    $formAction = $action ?? route('verification.send');
@endphp

<form method="POST" action="{{ $formAction }}" {{ $attributes }}>
    @csrf
    {{ $slot }}
</form>
