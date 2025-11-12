<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" />
  <x-meta-header />
</head>

<body>

  @include('ia.button')
  
  <div class="min-h-screen flex flex-col">
    <!-- Layouts Parts Header from layouts/app.blade -->
    @include('components.app.layout.header.this')
    @include('layouts.parts.status')
    @include('layouts.parts.main')
    @include('layouts.parts.footer')

  </div>



  <!-- Bottom widget from layouts/app.blade -->
  @stack('modals') @livewireScripts @stack('scripts')
</body>

</html>
