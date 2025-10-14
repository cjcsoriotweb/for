{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>403 — Accès interdit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="max-w-xl w-full rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-2xl font-semibold mb-2">403 — Accès interdit</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                @if(!empty($message))
                    {{ $message }}
                @else
                    Vous n’avez pas l’autorisation d’accéder à cette ressource.
                @endif
            </p>
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Retour
                </a>
                <a href="{{ route('home') }}" class="ml-2 inline-flex items-center rounded-lg bg-gray-200 px-4 py-2 text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                    Accueil
                </a>
            </div>
        </div>
    </div>

    {{-- Panneau debug permissions : visible si APP_DEBUG=true (ou adapte la condition) --}}
    <x-auth-debug-panel />

</body>
</html>
