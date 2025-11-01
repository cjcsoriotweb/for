<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assistant IA - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
    <x-meta-header/>
</head>
<body class="h-full">
    <div class="flex h-full flex-col text-white">
        <div class="flex-1 overflow-hidden px-4 py-4 sm:px-6 sm:py-6">
            <div class="mx-auto h-full w-full max-w-5xl">
                @livewire('ai.assistant-chat')
            </div>
        </div>
    </div>
    @livewireScriptConfig
    @livewireScripts
</body>
</html>
