@props([
    'icon' => null,
    'title' => 'Support',
])

<button
    type="button"
    {{ $attributes->merge([
        'class' => 'shortcut-button',
    ]) }}
    data-shortcut="support"
    aria-label="{{ $title }}"
>
    @if($icon)
        {!! $icon !!}
    @else
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 21c4.418 0 8-3.134 8-7 0-3.866-3.582-7-8-7-1.915 0-3.68.676-5 1.792" />
            <path d="M7.5 21 9 17l-3-2 1-3" />
            <path d="M12 10v2" />
            <path d="M12 16h.01" />
        </svg>
    @endif
</button>

