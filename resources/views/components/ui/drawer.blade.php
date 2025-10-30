@props([
    'open' => false,
    'side' => 'left',
    'maxWidth' => 'max-w-[420px]',
    'closeEvent' => null,
])

@php
    $panelBaseClasses = 'fixed inset-y-0 z-[61] w-full sm:w-auto transform transition-transform duration-300 will-change-transform';
    $panelPosition = $side === 'left' ? 'left-0' : 'right-0';
    $panelHiddenTransform = $side === 'left' ? '-translate-x-full' : 'translate-x-full';
    $panelVisibleTransform = 'translate-x-0';
@endphp

<div class="relative z-[60]" aria-hidden="{{ $open ? 'false' : 'true' }}">
    <div
        class="fixed inset-0 bg-slate-900/50 transition-opacity duration-300 {{ $open ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none' }}"
        @if($closeEvent)
            wire:click="{{ $closeEvent }}"
        @endif
    ></div>

    <div
        class="{{ $panelBaseClasses }} {{ $panelPosition }} {{ $open ? $panelVisibleTransform : $panelHiddenTransform }}"
        @class([$maxWidth])
        role="dialog"
        aria-modal="true"
    >
        <div class="flex h-full max-h-screen flex-col bg-white shadow-2xl dark:bg-slate-900">
            {{ $slot }}
        </div>
    </div>
</div>
