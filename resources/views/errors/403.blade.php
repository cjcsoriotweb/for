{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — {{ $siteName ?? config('app.name', 'Application') }}</title>

    {{-- Tailwind CDN (simple & autonome). En prod, passez plutôt par @vite. --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="robots" content="noindex,follow">
</head>
<body class="h-full bg-gradient-to-br from-slate-50 to-slate-100 text-slate-800 dark:from-slate-900 dark:to-slate-950 dark:text-slate-100">
    <main class="flex min-h-full flex-col items-center justify-center p-6">
        <div class="mx-auto w-full max-w-xl text-center">
            {{-- Logo / Nom site --}}
            <div class="mb-6 flex justify-center">
                @if (class_exists(\Illuminate\View\AnonymousComponent::class))
                    @if (View::exists('components.application-logo'))
                        <x-application-logo class="h-10 w-auto text-indigo-600" />
                    @else
                        <div class="rounded-xl bg-white/70 px-3 py-1 text-sm font-semibold text-indigo-700 shadow ring-1 ring-indigo-100">
                            {{ $siteName ?? config('app.name') }}
                        </div>
                    @endif
                @endif
            </div>

            

            {{-- Code 404 stylé --}}
            <h1 class="text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-fuchsia-600 select-none">
                403
            </h1>

            <p class="mt-4 text-xl font-semibold">
                Vous ne disposez pas des autorisations nécessaires pour accéder à cette page.
            </p>

            @php
                // Le message peut venir de $message (si tu le passes toi-même)
                // ou de l'exception HTTP ($exception).
                $errorMessage = $message
                    ?? ($exception?->getMessage() ?: null);
            @endphp

            @if ($errorMessage)
                <p class="mt-3 text-sm text-red-600/90 dark:text-red-400">
                    {{ $errorMessage }}
                </p>
            @else
                <p class="mt-2 text-slate-600 dark:text-slate-300">
                    Le lien peut être périmé, ou l’URL contient une faute de frappe.
                </p>
            @endif

            {{-- Actions --}}
            <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <button onclick="history.back()" class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-300 hover:bg-white dark:text-slate-100 dark:ring-slate-700/60 dark:hover:bg-slate-800">
                    ← Revenir en arrière
                </button>

                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/60">
                    Aller à l’accueil
                </a>
            </div>

            {{-- (Optionnel) barre de recherche interne --}}
            <form action="{{ url('/search') }}" method="GET" class="mt-8">
                <label class="sr-only" for="q">Rechercher</label>
                <div class="relative mx-auto max-w-md">
                    <input id="q" name="q" type="search" placeholder="Rechercher un contenu…"
                           class="w-full rounded-xl border border-slate-300 bg-white/70 px-4 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-700 dark:bg-slate-800/70">
                    <button class="absolute right-1 top-1 rounded-lg bg-slate-900/90 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-900">
                        Rechercher
                    </button>
                </div>
            </form>

            {{-- (Dev) Infos utiles en local --}}
            @if (app()->environment('local'))
                <p class="mt-6 text-xs text-slate-500">
                    <code>URL :</code> {{ request()->fullUrl() }}
                </p>
            @endif
        </div>
    </main>
</body>
</html>
