<x-app-layout>
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br  text-slate-800">
  

        <main class="relative z-10 px-4 py-16 sm:px-8 lg:px-14">
            <div class="mx-auto flex max-w-5xl flex-col gap-12">
                <div class="relative overflow-hidden rounded-3xl border border-white/70 bg-white/75 p-10  backdrop-blur-xl sm:p-12">
                        <div class="relative mx-auto max-w-3xl text-center">
                        <span class="inline-flex items-center gap-2 rounded-full bg-sky-100/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">
                            <span class="material-symbols-outlined text-sm">swap_horiz</span>
                            {{ __('Changement d espace') }}
                        </span>

                        <h1 class="mt-6 text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl">
                            {{ __('Choisissez votre destination au sein de') }}
                            <br>
                            <span class="bg-gradient-to-r from-sky-500 to-indigo-500 bg-clip-text text-transparent">
                                {{ $team->name }}
                            </span>
                        </h1>

                        <p class="mt-4 text-base text-slate-600 sm:text-lg">
                            {{ __("Vous devez choisir le rôle que vous souhaitez jouer.") }}
                        </p>

                        @if ($shouldAutoRedirect)
                            <div class="mt-7 inline-flex items-center gap-3 rounded-full border border-slate-200 bg-white/90 px-4 py-2 text-sm font-medium text-slate-700 shadow-sm">
                                <span class="material-symbols-outlined text-base text-sky-500">hourglass_top</span>
                                <span>
                                    {{ __('Redirection automatique dans') }}
                                    <span id="team-switch-countdown" class="font-semibold text-slate-900">
                                        {{ $countdownSeconds }}
                                    </span>
                                    {{ __('secondes') }}
                                </span>
                            </div>
                            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">
                                {{ __('Cliquez sur la carte ci-dessous pour partir tout de suite.') }}
                            </p>
                        @endif
                    </div>
                </div>

                @php
                    $hasMultipleDestinations = count($destinations) > 1;
                @endphp

                <div class="grid gap-6 sm:grid-cols-1">
                    @foreach ($destinations as $destination)
                        @php
                            $isSelected = $selectedRole === $destination['key'];
                        @endphp

                        <a
                            href="{{ $destination['route'] }}"
                            data-team-switch-choice
                            class="group relative overflow-hidden rounded-3xl border border-sky-200/60 bg-white/90 p-7 shadow-[0_20px_45px_-25px_rgba(30,64,175,0.3)] transition duration-500 hover:-translate-y-2 hover:border-sky-300/80 hover:shadow-[0_35px_75px_-40px_rgba(30,64,175,0.6)] hover:scale-[1.02] focus:outline-none focus-visible:ring-4 focus-visible:ring-sky-300/60"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br {{ $destination['gradient'] }} opacity-60 transition-opacity duration-400 group-hover:opacity-90"></div>
                            <div class="absolute inset-0 opacity-70 transition-opacity duration-400 group-hover:opacity-100">
                                <div class="absolute -top-16 right-0 h-56 w-56 rounded-full bg-white/30 blur-3xl"></div>
                            </div>

                            <div class="relative z-10 flex flex-col gap-6">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700 group-hover:bg-white/80 group-hover:text-slate-900">
                                        <span class="material-symbols-outlined text-base group-hover:text-sky-600">
                                            {{ $destination['icon'] }}
                                        </span>
                                        {{ $destination['badge'] }}
                                        @if ($hasMultipleDestinations)
                                            <span class="material-symbols-outlined text-xs text-slate-400 transition group-hover:text-sky-500">
                                                sync_alt
                                            </span>
                                        @endif
                                    </span>

                                    @if ($isSelected)
                                        <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-sky-600 group-hover:border-white group-hover:bg-white/85">
                                            <span class="material-symbols-outlined text-base">star</span>
                                            {{ __('Role actuel') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-5">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-2xl text-sky-500 transition duration-500 group-hover:scale-110 group-hover:bg-white/80 group-hover:text-white">
                                        <span class="material-symbols-outlined text-3xl group-hover:text-white">
                                            {{ $destination['icon'] }}
                                        </span>
                                    </div>

                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900 group-hover:text-white">
                                            {{ $destination['title'] }}
                                        </h2>
                                        <p class="mt-2 text-sm text-slate-600 transition duration-300 group-hover:text-white/85">
                                            {{ $destination['description'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2 text-sm font-medium text-slate-600 transition duration-300 group-hover:text-white">
                                    <span class="material-symbols-outlined text-lg transition duration-300 group-hover:translate-x-1 group-hover:scale-125">
                                        arrow_forward
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <a
                        href="{{ route('user.dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-full  bg-white px-5 py-2 text-sm font-medium text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-slate-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-sky-300/40"
                    >
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        {{ __('Retour au choix des equipes') }}
                    </a>
                </div>
            </div>
        </main>
    </div>

    @if ($shouldAutoRedirect && $autoRedirectUrl)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var redirectUrl = @json($autoRedirectUrl);
                var countdown = {{ max(1, (int) $countdownSeconds) }};
                var counterElement = document.getElementById('team-switch-countdown');

                var intervalId = window.setInterval(function () {
                    countdown -= 1;

                    if (counterElement) {
                        counterElement.textContent = countdown;
                    }

                    if (countdown <= 0) {
                        window.clearInterval(intervalId);
                        window.location.href = redirectUrl;
                    }
                }, 10);

                document.querySelectorAll('[data-team-switch-choice]').forEach(function (element) {
                    element.addEventListener('click', function () {
                        window.clearInterval(intervalId);
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
