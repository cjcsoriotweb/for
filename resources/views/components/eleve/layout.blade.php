@props(['team'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ config("app.name", "Laravel") }} - Espace Élève</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <a href="{{ route('eleve.index', $team) }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">
                {{ config("app.name", "Laravel") }} - Élève
              </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              <x-nav-link :href="route('eleve.index', $team)" :active="request()->routeIs('eleve.index')">
                {{ __("Accueil") }}
              </x-nav-link>
            </div>
          </div>

          <!-- User Menu -->
          <div class="hidden sm:flex sm:items-center sm:ml-6">
            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                <button
                  class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                  {{ Auth::user()->name }}

                  <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </button>
              </x-slot>

              <x-slot name="content">
                <!-- Account Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                  {{ __("Gérer le compte") }}
                </div>

                <x-dropdown-link :href="route('profile.show')">
                  {{ __("Profil") }}
                </x-dropdown-link>

                <x-dropdown-link :href="route('teams.show', $team)">
                  {{ __("Équipe") }}
                </x-dropdown-link>

                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __("Déconnexion") }}
                  </x-dropdown-link>
                </form>
              </x-slot>
            </x-dropdown>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-8">
      {{ $slot }}
    </main>
  </div>
</body>

</html>