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


  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">

              <x-nav-link :href="route('organisateur.index', $team)" :active="request()->routeIs('eleve.index')"
                class="text-xl font-bold text-gray-800 dark:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>


                {{ $team->name }}
              </x-nav-link>
            </div>
          </div>

          <!-- User Menu -->
          <div class="hidden sm:flex sm:items-center sm:ml-6 gap-3">
            @if ($canSwitchSpaces)
              <x-forms.account.switch-team :team="$team" class="inline-flex">
                <button
                  type="submit"
                  class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white/90 p-2 text-gray-600 transition hover:border-sky-300 hover:text-sky-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400"
                  title="{{ __('Basculer d\'espace') }}"
                >
                  <span class="sr-only">{{ __("Basculer d'espace") }}</span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-5 w-5"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18H5.25m0 0L8 20.75M5.25 18l2.75-2.75M7.5 6h11.25m0 0L16 3.25M18.75 6l-2.75 2.75" />
                  </svg>
                </button>
              </x-forms.account.switch-team>
            @endif

            <div name="trigger">
              <a href="{{route('user.dashboard')}}"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                {{ Auth::user()->name }}
              </a>
            </div>

          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-8">
      {{ $slot }}
    </main>
  </div>

  @livewireScripts
  @stack('scripts')
</x-app-layout>
