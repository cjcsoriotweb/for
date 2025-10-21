<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
  <title>
    @hasSection('title') @yield('title') — @endif{{
    config("app.name", "Application")
    }}
  </title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" />

  <x-header />
</head>

<body>
  <a href="#main-content"
    class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:text-blue-700 dark:focus:bg-gray-800">
    Aller au contenu
  </a>

  <x-banner />

  <div class="min-h-screen flex flex-col">
    {{-- En-tête de page optionnel --}}
    @isset($header)
    {{ $header }}
    @else
    <x-layout.header />
    @endisset

    {{-- 👉 Slot BLOCK optionnel (carte centrale au-dessus du contenu) --}}
    @isset($block)
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
        <div class="p-6 text-gray-700 dark:text-gray-100">
          {{ $block }}
        </div>
      </div>
    </div>
    @endisset

    {{-- Flash status éventuel --}}
    @if (session('status'))
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
      <div class="rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/40 dark:text-green-100">
        {{ session("status") }}
      </div>
    </div>
    @endif

    <main id="main-content" class="flex-1 py-8">
      <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="w-full">
          {{ $slot }}
        </div>
      </div>
    </main>

    <footer class="border-t border-gray-200 dark:border-gray-800 py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col space-y-4 text-center">
        <p>
          <b>© {{ now()->year }} {{ config("app.name") }} —
            {{ __("Tous droits réservés.") }}</b>
        </p>
        <p>
          <a href="{{ route('guest.policy') }}" class="text-blue-500 hover:text-blue-700">{{ __("Mentions légales")
            }}</a>
        </p>
      </div>
    </footer>
  </div>

  @stack('modals') @livewireScripts @stack('scripts')

  <style>
    /* Custom scrollbar for webkit browsers */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }

    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Dark mode scrollbar */
    .dark ::-webkit-scrollbar-thumb {
      background: #475569;
    }

    .dark ::-webkit-scrollbar-thumb:hover {
      background: #64748b;
    }

    /* Ensure Tailwind utilities are available */
    .bg-primary {
      background-color: #137fec;
    }

    .text-primary {
      color: #137fec;
    }

    .border-primary {
      border-color: #137fec;
    }

    .hover\:bg-primary:hover {
      background-color: #137fec;
    }

    .hover\:text-primary:hover {
      color: #137fec;
    }
  </style>
</body>

</html>