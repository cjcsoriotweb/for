<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    <style>
        html, body {
            background: transparent !important;
        }
    </style>
</head>
<body class="h-full bg-transparent text-white">
    <div class="flex h-full flex-col bg-transparent">
        <div class="flex-1 overflow-hidden px-4 py-6 sm:px-6 sm:py-8">
            <div class="mx-auto flex h-full w-full max-w-5xl">
                <div class="flex w-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-950/85 shadow-2xl shadow-slate-950/60 backdrop-blur">
                    @hasSection('dock-header')
                        <header class="border-b border-white/10 px-6 py-5 sm:px-8">
                            @yield('dock-header')
                        </header>
                    @endif

                    <main class="flex-1 overflow-hidden">
                        @yield('dock-content')
                    </main>

                    @hasSection('dock-footer')
                        <footer class="border-t border-white/10 px-6 py-5 sm:px-8">
                            @yield('dock-footer')
                        </footer>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @livewireScriptConfig
    @livewireScripts
    @stack('scripts')
</body>
</html>
