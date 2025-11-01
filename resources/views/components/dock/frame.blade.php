@props([
    'maxWidthClass' => 'max-w-5xl',
])

<div {{ $attributes->class('flex min-h-screen flex-col bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white') }}>
    <div class="relative flex-1 overflow-y-auto px-6 py-6 sm:px-8">
        <div class="mx-auto w-full {{ $maxWidthClass }}">
            {{ $slot }}
        </div>
    </div>
</div>
