{{-- resources/views/errors/500.blade.php --}}
<!DOCTYPE html>
<html lang="fr" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>
            500 — {{ $siteName ?? config("app.name", "Application") }}
        </title>

        <x-meta-header />

        <meta name="robots" content="noindex,follow" />
    </head>
    <body class="h-full bg-white text-gray-900">
        <main class="flex min-h-full flex-col items-center justify-center p-6">
            <div class="mx-auto w-full max-w-md text-center">
                {{-- Icône --}}
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Code 500 --}}
                <h1 class="text-6xl font-light text-gray-300 mb-4 select-none">
                    500
                </h1>

                <h2 class="text-2xl font-normal text-gray-800 mb-2">
                    Erreur technique
                </h2>

                @php $errorMessage = $message ?? ($exception?->getMessage() ?: null); @endphp
                @if($errorMessage)
                <p class="text-sm text-gray-600 mb-8">
                    {{ $errorMessage }}
                </p>
                @else
                <p class="text-gray-600 mb-8 leading-relaxed">
                    Une erreur technique s'est produite. Notre équipe a été notifiée.
                </p>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button
                        onclick="window.location.reload()"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        ↻ Actualiser
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
