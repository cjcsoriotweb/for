@props([
    'team',
    'icon' => 'admin_panel_settings',
    'title' => __('Tableau de bord administrateur'),
    'subtitle' => __("G\u{00E9}rez votre plateforme de formation"),
])

@php
    $teamParam = $team;
    $teamName = trim($teamParam->name ?? '');
    $teamInitials = 'EQ';

    if ($teamName !== '') {
        $firstChar = mb_substr($teamName, 0, 1, 'UTF-8');
        $secondChar = mb_substr($teamName, 1, 1, 'UTF-8');
        $teamInitials = mb_strtoupper($firstChar . ($secondChar ?: ''), 'UTF-8');
    }

    $breadcrumbs = [];
    $backUrl = null;
    $currentRoute = request()->route();

    if ($currentRoute && request()->routeIs('application.admin.index')) {
        $backUrl = route('application.admin.overview');
        $breadcrumbs = [
            ['label' => __('Accueil administrateur'), 'url' => $backUrl],
            ['label' => __('Tableau de bord'), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.configuration.credits')) {
        $backUrl = route('application.admin.configuration.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Configuration'), 'url' => route('application.admin.configuration.index', $teamParam)],
            ['label' => __("Cr\u{00E9}dits"), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.configuration.*')) {
        $backUrl = route('application.admin.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Tableau de bord'), 'url' => route('application.admin.index', $teamParam)],
            ['label' => __('Configuration'), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.users.*')) {
        $backUrl = route('application.admin.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Tableau de bord'), 'url' => route('application.admin.index', $teamParam)],
            ['label' => __('Utilisateurs'), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.formations.students.show')) {
        $formation = $currentRoute->parameter('formation');
        $backUrl = route('application.admin.formations.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Formations'), 'url' => route('application.admin.formations.index', $teamParam)],
            ['label' => optional($formation)->title ?? __('Formation'), 'url' => null],
            ['label' => __('Apprenant'), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.formations.revenue')) {
        $formation = $currentRoute->parameter('formation');
        $backUrl = route('application.admin.formations.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Formations'), 'url' => route('application.admin.formations.index', $teamParam)],
            ['label' => optional($formation)->title ?? __('Revenus'), 'url' => null],
        ];
    } elseif ($currentRoute && request()->routeIs('application.admin.formations.*')) {
        $backUrl = route('application.admin.index', $teamParam);
        $breadcrumbs = [
            ['label' => __('Tableau de bord'), 'url' => route('application.admin.index', $teamParam)],
            ['label' => __('Formations'), 'url' => null],
        ];
    } else {
        $previousUrl = url()->previous();
        $currentUrl = url()->full();

        if (empty($previousUrl) || $previousUrl === $currentUrl) {
            $previousUrl = route('application.admin.index', $teamParam);
        }

        $backUrl = $previousUrl;
        $breadcrumbs = [
            ['label' => __('Tableau de bord'), 'url' => route('application.admin.index', $teamParam)],
        ];
    }

    $hasBreadcrumbs = ! empty($breadcrumbs);
@endphp

@php($headerHasOwnActions = true)

<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white shadow-2xl">
            <div class="absolute -top-24 -left-16 h-60 w-60 rounded-full bg-indigo-500/20 blur-3xl"></div>
            <div class="absolute -bottom-28 -right-16 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>

            <div class="relative space-y-8 p-8 lg:p-10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($backUrl)
                            <a
                                href="{{ $backUrl }}"
                                class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 transition hover:bg-white/20 hover:text-white"
                            >
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                {{ __('Retour') }}
                            </a>
                        @endif

                        @if ($hasBreadcrumbs)
                            <nav aria-label="{{ __('Fil d\'Ariane') }}">
                                <ol class="flex flex-wrap items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.35em] text-white/60">
                                    @foreach ($breadcrumbs as $index => $crumb)
                                        @if ($index)
                                            <li class="text-white/30">/</li>
                                        @endif

                                        <li>
                                            @if (! empty($crumb['url']))
                                                <a href="{{ $crumb['url'] }}" class="transition hover:text-white">{{ $crumb['label'] }}</a>
                                            @else
                                                <span class="text-white/85">{{ $crumb['label'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        @isset($headerActions)
                            {{ $headerActions }}
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 transition hover:bg-white/20 hover:text-white"
                                >
                                    <span class="material-symbols-outlined text-base">logout</span>
                                    {{ __("D\u{00E9}connexion") }}
                                </button>
                            </form>
                        @endisset
                    </div>
                </div>

                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg">
                                <span class="material-symbols-outlined text-2xl text-white">{{ $icon }}</span>
                            </div>

                            <div>
                                <h1 class="text-3xl font-semibold leading-tight text-white">
                                    {{ $title }}
                                </h1>

                                @if (! empty($subtitle))
                                    <p class="mt-2 text-sm text-white/70">
                                        {{ $subtitle }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-4 rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur">
                            <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-xl border border-white/20 bg-white/10 text-2xl font-semibold text-white">
                                @if ($team->profile_photo_path)
                                    <img
                                        src="{{ Storage::disk('public')->url($team->profile_photo_path) }}"
                                        alt="{{ __("Logo de l'\u{00E9}quipe :name", ['name' => $teamName ?: __('Sans nom')]) }}"
                                        class="h-full w-full rounded-xl object-contain"
                                    />
                                @else
                                    <span>{{ $teamInitials }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">
                                    {{ __("\u{00C9}quipe") }}
                                </p>
                                <p class="text-lg font-semibold text-white">
                                    {{ $teamName !== '' ? $teamName : __('Sans nom') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        {{ $slot }}
    </div>
</x-application-layout>
