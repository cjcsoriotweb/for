<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <x-meta-header />
</head>

<body>
  <div class="font-sans text-gray-900 antialiased">
    {{ $slot }}
  </div>


  @livewireScripts
  @stack('scripts')
</body>

</html>
