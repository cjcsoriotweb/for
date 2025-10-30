<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
  <title>
    @hasSection('title') @yield('title') — @endif{{
    config("app.name", "Application")
    }}
  </title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <x-meta-header />
</head>

<body>
  <x-banner />

  <div class="min-h-screen flex flex-col">
    <!-- Layouts Parts Header from layouts/app.blade -->
    @include('layouts.parts.header')

    <!--
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
    !-->

    @include('layouts.parts.status')
    @include('layouts.parts.main')
    @include('layouts.parts.footer')

  </div>

  <!-- Bottom widget from layouts/app.blade -->
  @auth
  <livewire:support.chat-widget :show-launcher="false" />
  @endauth

  @stack('modals') @livewireScripts @stack('scripts')
</body>

</html>
