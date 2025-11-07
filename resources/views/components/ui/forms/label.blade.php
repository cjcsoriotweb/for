@props(['value'])

@php
    $defaultClasses = 'block font-medium text-sm text-gray-700';
    $additionalClasses = $attributes->get('class', '');
    $classes = $additionalClasses ? $additionalClasses . ' ' . $defaultClasses : $defaultClasses;
    $attributes = $attributes->except('class');
@endphp

<label class="{{ $classes }}" {{ $attributes }}>
    {{ $value ?? $slot }}
</label>
