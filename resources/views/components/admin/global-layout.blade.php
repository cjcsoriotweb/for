@props([
    'icon' => 'admin_panel_settings',
    'title' => __('Administration centrale'),
    'subtitle' => __('Supervisez l’ensemble des équipes et des utilisateurs.'),
])

<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950">
            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:py-12">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-500/20 text-indigo-200 ring-1 ring-inset ring-indigo-500/40">
                        <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white sm:text-4xl">
                            {{ $title }}
                        </h1>
                        <p class="mt-2 text-sm text-indigo-100/90">
                            {{ $subtitle }}
                        </p>
                    </div>
                </div>

                @isset($headerActions)
                    <div class="flex flex-wrap items-center gap-3">
                        {{ $headerActions }}
                    </div>
                @endisset
            </div>
        </div>
    </x-slot>

    {{ $slot }}
</x-app-layout>
