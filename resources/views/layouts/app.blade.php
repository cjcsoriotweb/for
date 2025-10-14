<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') — @endif{{ config('app.name', 'Application') }}</title>

    {{-- Favicons (optionnel) --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Vite (Laravel 12) : CSS & JS app --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Livewire --}}
    @livewireStyles

    {{-- En-tête personnalisée (styles/links additionnels) --}}
    <x-header />

    {{-- Pile extensible pour des <meta> ou <link> additionnels --}}
    @stack('head')
</head>

<body class="h-full bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50
       focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:text-blue-700 dark:focus:bg-gray-800">
        Aller au contenu
    </a>

    <x-banner />

    <div class="min-h-screen flex flex-col">

        {{-- 👉 Slot NAV optionnel (pour ton menu équipe) --}}
        {{ $nav ?? '' }}

        {{-- En-tête de page optionnel --}}
        @isset($header)
            <header class="bg-white/70 backdrop-blur-sm dark:bg-gray-800/60 shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                    {{ $header }}
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
                © {{ now()->year }} {{ config('app.name') }} — Tous droits réservés.
            </div>
        </footer>
    </div>

    @stack('modals')
    @livewireScripts
    @stack('scripts')
</body>
</html>