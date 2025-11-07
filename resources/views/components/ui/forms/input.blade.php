@props(['disabled' => false])

@php
    $defaultClasses = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
    $additionalClasses = $attributes->get('class', '');
    $classes = $additionalClasses ? $additionalClasses . ' ' . $defaultClasses : $defaultClasses;
    $attributes = $attributes->except('class');
@endphp

<input {{ $disabled ? 'disabled' : '' }} class="{{ $classes }}" {!! $attributes !!}>
