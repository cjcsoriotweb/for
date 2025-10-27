@props(['team'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ config('app.name', 'Laravel') }} - Espace &Eacute;l&egrave;ve</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-50">
  <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-900">
    <!-- Navigation -->
    <nav
      class="relative border-b border-white/10 bg-gradient-to-r from-slate-950/95 via-slate-900/88 to-slate-900/80 text-slate-100 backdrop-blur">
      <div
        class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.14),transparent_55%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.16),transparent_60%)]">
      </div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <x-nav-link :href="route('eleve.index', $team)" :active="request()->routeIs('eleve.index')"
                class="inline-flex items-center gap-2  px-4 py-2 text-xl font-semibold text-slate-100 transition hover:text-white hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ">
                {{ $team->name }}
              </x-nav-link>
            </div>
          </div>

          <!-- User Menu -->
          <div class="hidden sm:flex sm:items-center sm:ml-6">
            <div name="trigger">
              <a href="{{ route('user.dashboard') }}"
                class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-white/40 hover:bg-white/25 focus:outline-none focus:ring-2 focus:ring-sky-400/60">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400/90"></span>
                {{ Auth::user()->name }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main>
      {{ $slot }}
    </main>
  </div>
</body>

</html>