<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
  @stack('head')
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
