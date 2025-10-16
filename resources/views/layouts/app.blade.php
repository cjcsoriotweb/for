<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <title>@hasSection('title')@yield('title') — @endif{{ config('app.name', 'Application') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
    <x-header />
    @stack('head')
</head>

<body class="h-full bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50
       focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:text-blue-700 dark:focus:bg-gray-800">
        Aller au contenu
    </a>

    <x-banner />

    <div class="min-h-screen flex flex-col">
        {{ $nav ?? '' }}

        {{-- En-tête de page optionnel --}}
        @isset($header)
            <header class="bg-white bg-opacity-70 backdrop-blur-sm shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                    <a >{{ $header }}</a>
                </div>
            </header>
        @endisset
        

        {{-- 👉 Slot BLOCK optionnel (carte centrale au-dessus du contenu) --}}
        @isset($block)
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-700 dark:text-gray-100">
                        {{ $block }}
                    </div>
                </div>
            </div>
        @endisset

        {{-- Flash status éventuel --}}
        @if (session('status'))
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/40 dark:text-green-100">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main id="main-content" class="flex-1 py-8">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="border-t border-gray-200 dark:border-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 dark:text-gray-400">
                <p><b>© {{ now()->year }} {{ config('app.name') }} — {{ __('Tous droits réservés.') }}</b></p>
                <p>
                    <a href="{{ route('policy') }}">{{ __('Mentions légales') }}</a>
                </p>
            </div>
        </footer>

        
          
    <div class="py-12">
        <a href="{{ route('superadmin.home') }}" type="button"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Superadmin') }}</a>
    </div>
    </div>

    {{-- Panneau de debug Auth (Gate/Policy) --}}

    @stack('modals')
    @livewireScripts
    @stack('scripts')
    <x-auth-debug-panel />

</body>
</html>
