{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="fr" class="h-full">
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
    <body class="h-full bg-white text-gray-900">
        <main class="flex min-h-full flex-col items-center justify-center p-6">
            <div class="mx-auto w-full max-w-md text-center">
                {{-- Icône --}}
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-.978-5.625-2.509M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Code 404 --}}
                <h1 class="text-6xl font-light text-gray-300 mb-4 select-none">
                    404
                </h1>

                <h2 class="text-2xl font-normal text-gray-800 mb-2">
                    Page introuvable
                </h2>

                @php $errorMessage = $message ?? ($exception?->getMessage() ?: null); @endphp
                @if($errorMessage)
                <p class="text-sm text-gray-600 mb-8">
                    {{ $errorMessage }}
                </p>
                @else
                <p class="text-gray-600 mb-8 leading-relaxed">
                    La page que vous cherchez n'existe pas ou a été déplacée.
                </p>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button
                        onclick="history.back()"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        ← Retour
                    </button>

                    <a
                        href="{{ auth()->check() ? route('user.dashboard') : url('/') }}"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors"
                    >
                        Accueil
                    </a>
                </div>

                {{-- (Dev) Infos utiles en local --}}
                @if (app()->environment('local'))
                <p class="mt-8 text-xs text-gray-400">
                    <code>URL :</code> {{ request()->fullUrl() }}
                </p>
                @endif
            </div>
        </main>
    </body>
</html>
