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
        <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-6 shadow-sm shadow-slate-200/70 dark:border-slate-700/60 dark:bg-slate-900/80 dark:shadow-none">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    @if ($backUrl)
                        <a
                            href="{{ $backUrl }}"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:text-white"
                        >
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            {{ __('Retour') }}
                        </a>
                    @endif

                    @if ($hasBreadcrumbs)
                        <nav aria-label="{{ __('Fil d\'Ariane') }}">
                            <ol class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
                                @foreach ($breadcrumbs as $index => $crumb)
                                    @if ($index)
                                        <li class="text-slate-300 dark:text-slate-600">•</li>
                                    @endif

                                    <li>
                                        @if (! empty($crumb['url']))
                                            <a href="{{ $crumb['url'] }}" class="transition hover:text-slate-700 dark:hover:text-slate-300">
                                                {{ $crumb['label'] }}
                                            </a>
                                        @else
                                            <span class="text-slate-500 dark:text-slate-300">
                                                {{ $crumb['label'] }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @isset($headerActions)
                        {{ $headerActions }}
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:text-white"
                            >
                                <span class="material-symbols-outlined text-base">logout</span>
                                {{ __("D\u{00E9}connexion") }}
                            </button>
                        </form>
                    @endisset
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                        <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ $title }}
                        </h1>

                        @if (! empty($subtitle))
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                                {{ $subtitle }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-white">
                            @if ($team->profile_photo_path)
                                <img
                                    src="{{ Storage::disk('public')->url($team->profile_photo_path) }}"
                                    alt="{{ __("Logo de l'\u{00E9}quipe :name", ['name' => $teamName ?: __('Sans nom')]) }}"
                                    class="h-full w-full object-contain"
                                />
                            @else
                                <span class="text-lg font-semibold">{{ $teamInitials }}</span>
                            @endif
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                {{ __("\u{00C9}quipe") }}
                            </p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $teamName !== '' ? $teamName : __('Sans nom') }}
                            </p>
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
