@props(['team'])

@php
    $teamService = app(\App\Services\Clean\Account\TeamService::class);
    $availableDestinations = $teamService->availableDestinations(Auth::user(), $team);
    $canSwitchSpaces = count($availableDestinations) > 1;
@endphp

@push('head')
    <title>{{ config('app.name', 'Laravel') }} - Espace &Eacute;l&egrave;ve</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :team="$team">
  <div class="min-h-screen ">
    <main>
      {{ $slot }}
    </main>
  </div>
  <x-ui.layout.assistant-dock />
  
  @livewireScripts
</x-app-layout>
