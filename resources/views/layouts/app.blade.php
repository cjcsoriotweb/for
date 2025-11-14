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
    @php($impersonation = session('superadmin_impersonation'))
    @if (!empty($impersonation))
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
            <div class="rounded-2xl border border-yellow-300 bg-yellow-50/90 px-4 py-3 text-sm text-yellow-900 shadow-sm shadow-yellow-200 dark:border-yellow-500/40 dark:bg-yellow-900/70 dark:text-yellow-100">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold">
                            {{ __('Vous êtes connecté en tant que :target (IP : :ip).', [
                                'target' => $impersonation['target_user_name'] ?? __('cet utilisateur'),
                                'ip' => $impersonation['ip_address'] ?? __('non renseignée'),
                            ]) }}
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-200/80">
                            {{ __('Cliquez sur le bouton pour retrouver votre session Super-Admin.') }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('superadmin.impersonation.stop') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-full border border-yellow-500 bg-yellow-500/80 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-yellow-500 dark:border-yellow-400 dark:bg-yellow-400/90 dark:text-slate-900"
                        >
                            <span class="material-symbols-outlined text-base">settings_backup_restore</span>
                            {{ __('Revenir au Super-Admin') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @include('layouts.parts.status')
    @include('layouts.parts.main')
    @include('layouts.parts.footer')

  </div>



  <!-- Bottom widget from layouts/app.blade -->
  @stack('modals') @livewireScripts @stack('scripts')
</body>

</html>
