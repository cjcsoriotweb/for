<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name') . ' · Tutoriel')</title>
    <link rel="stylesheet" href="https://unpkg.com/fullpage.js/dist/fullpage.min.css">
    @stack('tutorial-styles')
    <x-meta-header/>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <header class="fixed inset-x-0 top-0 z-50 bg-slate-900/80 backdrop-blur border-b border-slate-800">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-4">
            <div>
                <h1 class="text-lg font-semibold">
                    @yield('header-title', __('Tutoriel'))
                </h1>
                <p class="text-sm text-slate-300">
                    @yield('header-subtitle')
                </p>
            </div>
            @if (isset($tutorialKey) && !($forced ?? false))
                <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}">
                    @csrf
                    <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                    <button type="submit" class="rounded-md border border-slate-600 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                        {{ __('Passer le tutoriel') }}
                    </button>
                </form>
            @elseif (($forced ?? false))
                <span class="rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-amber-200">
                    {{ __('Lecture obligatoire') }}
                </span>
            @endif
        </div>
    </header>

    <main id="fullpage" class="pt-24">
        @yield('content')
    </main>

    <script src="https://unpkg.com/fullpage.js/dist/fullpage.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('fullpage');

            if (!container) {
                return;
            }

            if (typeof fullpage_api !== 'undefined') {
                fullpage_api.destroy('all');
            }

            new fullpage('#fullpage', {
                licenseKey: 'gplv3-license',
                navigation: true,
                scrollOverflow: true,
                credits: {
                    enabled: false,
                },
            });
        });
    </script>
    @stack('tutorial-scripts')
</body>
</html>
