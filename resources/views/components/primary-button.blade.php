@props([
    'type' => 'submit',
    'href' => null,
    'disabled' => false,
])

@php
$classes = 'inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold
            bg-indigo-600 text-white shadow-sm
            hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50
            disabled:opacity-50 disabled:pointer-events-none transition';
@endphp

@if($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }} @disabled($disabled)>
        {{ $slot }}
    </button>
@endif
