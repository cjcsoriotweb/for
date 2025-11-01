<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <title>Support - {{ config('app.name') }}</title>
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
