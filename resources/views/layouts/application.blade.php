<x-app-layout>

    {{-- NAV : ton menu d'équipe --}}
    <x-slot name="nav">
        <x-team-navigation-menu :team="$team" />
    </x-slot>

    {{-- HEADER PROFESSIONNEL --}}
    @isset($header)
        <x-slot name="header">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm text-slate-300">{{ $headerIcon ?? 'school' }}</span>
                            </div>
                            <div>
                                {{ $header }}
                                @isset($subtitle)
                                    <p class="text-sm text-slate-300">{{ $subtitle }}</p>
                                @endisset
                            </div>
                        </div>
                        @isset($headerActions)
                            <div class="flex items-center space-x-3">
                                {{ $headerActions }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </x-slot>
    @else
        <x-slot name="header">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm text-slate-300">dashboard</span>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white">{{ config('app.name', 'Application') }}</h1>
                                <p class="text-sm text-slate-300">Espace équipe professionnel</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-slate-300">
                            <span class="material-symbols-outlined">business</span>
                            <span>{{ $team->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    @endisset

    {{-- BLOCK ÉLÉGANT --}}
    @isset($block)
        <x-slot name="block">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 mx-4 sm:mx-6 lg:mx-8 -mt-8 relative z-10 mb-8">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">info</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">
                                {{ $block }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    @endisset

    @isset($slot)
        {{ $slot }}
    @endisset

    {{-- Zone d'erreurs / alertes modernisées --}}
    <x-error-display />

    {{-- CONTENU PRINCIPAL AVEC ESPACE --}}
    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </div>

</x-app-layout>
