@props(['team'])

@php
    $teamService = app(\App\Services\Clean\Account\TeamService::class);
    $availableDestinations = $teamService->availableDestinations(Auth::user(), $team);
    $canSwitchSpaces = count($availableDestinations) > 1;
@endphp

@push('head')
    <title>{{ config("app.name", "Laravel") }} - Espace Organisateur</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :team="$team">
  {{-- Messages de notification --}}
  @if(session('success'))
  <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
    {{ session("success") }}
  </div>
  @endif @if(session('warning'))
  <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
    {{ session("warning") }}
  </div>
  @endif @if(session('error'))
  <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
    {{ session("error") }}
  </div>
  @endif


  <div class="min-h-screen ">


    <!-- Page Content -->
    <main class="py-8">
      {{ $slot }}
    </main>
  </div>

  @livewireScripts
  @stack('scripts')
</x-app-layout>
