<x-app-layout>

    {{-- NAV : ton menu d'équipe --}}
    <x-slot name="nav">
        <x-team-navigation-menu :team="$team" />
    </x-slot>

    {{-- HEADER PROFESSIONNEL --}}
    @isset($header)
        <x-slot name="header">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg floating-element">
                                <span class="material-symbols-outlined text-lg text-white">{{ $headerIcon ?? 'school' }}</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">{{ $header }}</h2>
                                @isset($subtitle)
                                    <p class="text-slate-300 mt-1">{{ $subtitle }}</p>
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
            <div class="bg-gradient-to-br from-primary-900 via-slate-900 to-slate-800 relative overflow-hidden">
                <div class="absolute inset-0 magic-bg opacity-20"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                                <span class="material-symbols-outlined text-2xl text-white">dashboard</span>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ config('app.name', 'Application') }}</h1>
                                <p class="text-slate-300 mt-1">Espace équipe professionnel</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 text-slate-300 bg-white/5 backdrop-blur-sm rounded-xl px-4 py-2 border border-white/10">
                            <span class="material-symbols-outlined text-xl">business</span>
                            <span class="font-medium">{{ $team->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    @endisset

    {{-- BLOCK ÉLÉGANT --}}
    @isset($block)
        <x-slot name="block">
            <div class="glass-card mx-4 sm:mx-6 lg:mx-8 -mt-8 relative z-10 mb-8 bounce-in">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="material-symbols-outlined text-primary-600 dark:text-primary-400">info</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
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
