@props([
    'team',
])

@php
    $siteLogoUrl = asset('logo-dark.png');
    $teamLogoUrl = $team?->profile_photo_url ?? asset('logo.png');
    $siteAlt = config('app.name').' - logo du site';
    $teamAlt = ($team->name ?? config('app.name')).' - logo de l\'application';
@endphp

@once
    <style>
        @keyframes identity-logo-left {
            0%   { opacity: 0; transform: translateX(-260px) scale(0.75); filter: blur(12px); }
            55%  { opacity: 1; transform: translateX(18px) scale(1.05); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes identity-logo-right {
            0%   { opacity: 0; transform: translateX(260px) scale(0.75); filter: blur(12px); }
            55%  { opacity: 1; transform: translateX(-18px) scale(1.05); filter: blur(0); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }
        .identity-panel-motion {
            background-color: #ffffff;
        }
        .identity-logo-left {
            animation: identity-logo-left 1.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        .identity-logo-right {
            animation: identity-logo-right 1.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        @media (prefers-reduced-motion: reduce) {
            .identity-logo-left,
            .identity-logo-right {
                animation: none !important;
            }
        }
    </style>
@endonce

<div class="identity-panel-motion relative overflow-hidden rounded-[2.5rem] bg-white px-8 py-12">
    <div class="relative flex flex-col items-center gap-8 sm:flex-row sm:justify-center sm:gap-24">
        <img
            src="{{ $siteLogoUrl }}"
            alt="{{ $siteAlt }}"
            class="identity-logo-left h-28 w-auto"
            loading="lazy"
        >
        <div class="hidden h-16 w-px bg-slate-200 sm:block" aria-hidden="true"></div>
        <img
            src="{{ $teamLogoUrl }}"
            alt="{{ $teamAlt }}"
            class="identity-logo-right h-28 w-auto"
            loading="lazy"
        >
    </div>
</div>
