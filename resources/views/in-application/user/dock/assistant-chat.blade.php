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
<body class="h-full bg-slate-100">

            @livewire('ai.assistant-chat')

    @livewireScriptConfig
    @livewireScripts
</body>
</html>
