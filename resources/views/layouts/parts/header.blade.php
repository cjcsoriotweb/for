{{-- En-tête de page optionnel --}}
    @isset($header)
    {{ $header }}
    @else
    <x-layout.header />
    @endisset