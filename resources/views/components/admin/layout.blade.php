@props([
    'team',
    'icon' => 'admin_panel_settings',
    'title' => __('Tableau de bord administrateur'),
    'subtitle' => __('Gérez votre plateforme de formation'),
])

<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600">
                    <span class="material-symbols-outlined text-xl text-white">{{ $icon }}</span>
                </div>

                <div>
                    <h2 class="text-xl font-bold leading-tight text-white">
                        {{ $title }}
                    </h2>
                    @if (! empty($subtitle))
                        <p class="text-sm text-blue-100">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            @isset($headerActions)
                <div class="flex items-center gap-3">
                    {{ $headerActions }}
                </div>
            @endisset
        </div>
    </x-slot>

    <div class=" p-5">
        {{ $slot }}
    </div>
</x-application-layout>
