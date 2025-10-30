@props([
    'name' => '',
    'mode' => 'chatia', // chatia, iframe, tutor, search, support
    'title' => '',
    'icon' => '',
    'action' => 'chatia',
    'chatmodel' => null,
    'chatprovider' => null,
    'enabled' => true,
    'locked' => false,
    'wizz' => false,
    'notify' => 0,
])

@php
    // Déterminer la couleur du bouton selon le mode
    $buttonClassMap = [
        'chatia' => 'bg-slate-900 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-slate-900',
        'tutor' => 'bg-amber-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-amber-500',
        'tutorial' => 'bg-green-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-green-500',
        'search' => 'bg-indigo-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-indigo-500',
        'support' => 'bg-sky-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-sky-500',
    ];
    $buttonClasses = $buttonClassMap[$mode] ?? 'bg-gray-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-gray-500';

    // Déterminer l'icône selon le mode si pas spécifiée
    $iconMap = [
        'chatia' => '<path d="M7 8h10"></path><path d="M7 12h6"></path><path d="M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z"></path>',
        'tutor' => '<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path><path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path><path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path><path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>',
        'tutorial' => '<path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479l-6.16 3.422L5.839 17.057a12.083 12.083 0 01.665-6.479L12 14z"></path><path d="M12 14l0 7"></path>',
        'search' => '<circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.35-4.35"></path>',
        'support' => '<path d="M10 12v-1"></path><path d="M14 12v-1"></path><path d="M12 6a5 5 0 0 0-5 5v1a7 7 0 0 0 4 6v1a1 1 0 0 0 2 0v-1a7 7 0 0 0 4-6v-1a5 5 0 0 0-5-5z"></path><path d="M7 10H5"></path><path d="M19 10h-2"></path><path d="M7 16l-2 2"></path><path d="M19 16l2 2"></path><path d="M7 4l1.5 1.5"></path><path d="M17 4 15.5 5.5"></path>',
    ];
    $defaultIcon = $iconMap[$mode] ?? '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>';
    $svgIcon = $icon ?: $defaultIcon;

    // Générer l'action JavaScript selon le mode
    $actionMap = [
        'chatia' => 'window.Livewire && window.Livewire.dispatch ? window.Livewire.dispatch(\'assistant-toggle\') : window.dispatchEvent(new CustomEvent(\'assistant-toggle\'))',
        'tutor' => 'window.Livewire && window.Livewire.dispatch ? window.Livewire.dispatch(\'tutor-toggle\') : window.dispatchEvent(new CustomEvent(\'tutor-toggle\'))',
        'support' => 'window.Livewire && window.Livewire.dispatch ? window.Livewire.dispatch(\'support-toggle\') : window.dispatchEvent(new CustomEvent(\'support-toggle\'))',
        'page-search' => 'window.Livewire && window.Livewire.dispatch ? window.Livewire.dispatch(\'page-search-toggle\') : window.dispatchEvent(new CustomEvent(\'page-search-toggle\'))',
    ];
    $clickAction = $actionMap[$action] ?? '';
@endphp

@if($enabled)
<button type="button"
        data-dock-title="{{ $title }}"
        data-dock-target="{{ $name }}"
        data-dock-enabled="1"
        data-dock-locked="{{ $locked ? '1' : '0' }}"
        data-dock-wizz="{{ $wizz ? '1' : '0' }}"
        data-dock-notify="{{ $notify }}"
        class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl {{ $buttonClasses }} @if($wizz) animate-pulse @endif @if($locked) opacity-60 cursor-not-allowed @endif"
        aria-label="{{ $title }}"
        title="{{ $title }}"
        @if(!$locked && $clickAction) onclick="{{ $clickAction }}" @endif>
    <span data-dock-indicator class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
    <span data-dock-badge class="absolute -top-1 -right-1 hidden min-w-[1.25rem] rounded-full bg-rose-600 px-1 text-center text-[10px] font-semibold leading-5 text-white shadow"></span>
    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        {!! $svgIcon !!}
    </svg>
</button>
@endif
