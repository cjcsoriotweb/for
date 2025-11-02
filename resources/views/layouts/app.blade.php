<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
  <link rel="icon" href="{{ asset('favicon.ico') }}" />
  <x-meta-header />
</head>

<body>
  <x-banner />

  <div class="min-h-screen flex flex-col">
    <!-- Layouts Parts Header from layouts/app.blade -->
    @include('layouts.parts.header')
    @include('layouts.parts.status')
    @include('layouts.parts.main')
    @include('layouts.parts.footer')

  </div>


  <x-ui.layout.assistants-ia-menu />

  @stack('assistant-dock')

  <!-- Bottom widget from layouts/app.blade -->
  @stack('modals') @livewireScripts @stack('scripts')
</body>

</html>
