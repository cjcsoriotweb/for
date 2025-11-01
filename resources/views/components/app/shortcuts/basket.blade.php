@props([
    /**
     * Accepted values: bottom-left, bottom-right, top-left, top-right
     */
    'position' => 'bottom-left',
    'gap' => 'gap-3',
])

@php
    $positionClasses = [
        'bottom-left' => 'bottom-6 left-6',
        'bottom-right' => 'bottom-6 right-6',
        'top-left' => 'top-6 left-6',
        'top-right' => 'top-6 right-6',
    ][$position] ?? 'bottom-6 left-6';
@endphp

<div class="fixed z-50 {{ $positionClasses }}">
    <div {{ $attributes->merge([
        'class' => "flex items-end {$gap}",
    ]) }}>
        {{ $slot }}
    </div>
</div>
