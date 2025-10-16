<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
    <title>
        @hasSection('title')
            @yield('title') —
        @endif{{ config('app.name', 'Application') }}
    </title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @livewireStyles
    <x-header />
    @stack('head')
</head>

<body>
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50
       focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:text-blue-700 dark:focus:bg-gray-800">
        Aller au contenu
    </a>

    <x-banner />

    <div class="min-h-screen flex flex-col">

        {{-- En-tête de page optionnel --}}
        @isset($header)
            {{ $header }}
        @else
            <div class="bg-white bg-opacity-80 backdrop-blur-sm shadow-sm flex items-center justify-between py-4 px-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('logout') }}"
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:text-gray-100 dark:bg-gray-800 dark:hover:bg-gray-900 focus:ring-gray-300">Se
                        déconnecter</a>
                </div>
            </div>
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

        <footer class="border-t border-gray-200 dark:border-gray-800 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col space-y-4 text-center">
                <p><b>© {{ now()->year }} {{ config('app.name') }} — {{ __('Tous droits réservés.') }}</b></p>
                <p>
                    <a href="{{ route('policy') }}" class="text-blue-500 hover:text-blue-700">{{ __('Mentions légales') }}</a>
                </p>
            </div>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-center">
                <a href="{{ route('superadmin.home') }}" type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-4 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Superadmin') }}</a>
            </div>
        </footer>


    </div>

    {{-- Panneau de debug Auth (Gate/Policy) --}}

    @stack('modals')
    @livewireScripts
    @stack('scripts')
    <x-auth-debug-panel />

</body>

</html>
