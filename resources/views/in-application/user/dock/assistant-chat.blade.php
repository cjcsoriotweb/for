<!DOCTYPE html>
<html lang="fr" class="h-full bg-transparent">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assistant IA - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
    <x-meta-header/>
    <style>
        html, body { background: transparent !important; }
    </style>
</head>
<body class="h-full bg-transparent">
    <div class="flex h-full flex-col bg-transparent text-white">
        <div class="flex-1 overflow-hidden px-4 py-4 sm:px-6 sm:py-6">
            <div class="mx-auto h-full w-full max-w-5xl bg-transparent">
                @livewire('ai.assistant-chat')
            </div>
        </div>
    </div>
    @livewireScriptConfig
    @livewireScripts
</body>
</html>
