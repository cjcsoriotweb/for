<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Support - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
    <x-meta-header/>
</head>
<body class="min-h-full bg-slate-950">
@php
    $originLabel = request()->input('origin_label') ?: 'Dock Signaler un bug';
@endphp

    <x-dock.frame>
        @livewire('support.ticket-reporter', [
            'originPath' => request()->input('origin'),
            'originLabel' => $originLabel,
        ])
    </x-dock.frame>

    @livewireScriptConfig
    @livewireScripts
</body>
</html>
