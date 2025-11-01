<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assistant IA - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-slate-100">
    <div class="flex h-full w-full overflow-hidden bg-slate-100 px-3 py-4">
        <div class="flex w-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <livewire:ai.assistant-chat />
        </div>
    </div>

    @livewireScriptConfig
    @livewireScripts
</body>
</html>
