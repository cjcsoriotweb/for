@props([
    'team',
])

@php
    $siteLogoUrl = asset('logo-dark.png');
    $teamLogoUrl = $team?->profile_photo_url ?? asset('logo.png');
    $siteAlt = config('app.name').' - logo du site';
    $teamAlt = ($team->name ?? config('app.name')).' - logo de l\'application';
    $teamName = $team->name ?? config('app.name');
@endphp

@once
    <style>
        @keyframes identity-panel-overlay {
            0%   { opacity: 0; backdrop-filter: blur(0px); }
            10%  { opacity: 1; backdrop-filter: blur(22px); }
            70%  { opacity: 1; backdrop-filter: blur(22px); }
            80%  { opacity: 1; backdrop-filter: blur(18px); }
            100% { opacity: 0; backdrop-filter: blur(0px); visibility: hidden; }
        }
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
        .identity-panel-overlay {
            animation: identity-panel-overlay 5.4s ease forwards;
            background-color: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(22px);
            pointer-events: none;
        }
        .identity-panel-motion {
            background-color: transparent;
        }
        .identity-logo-left {
            animation:
                identity-logo-left 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards,
                identity-logo-left-exit 0.5s ease 1.4s forwards;
        }
        .identity-logo-right {
            animation:
                identity-logo-right 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards,
                identity-logo-right-shift 0.8s ease 2.1s forwards;
        }
        @keyframes identity-team-info-enter {
            0% { opacity: 0; transform: translateY(12px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes identity-logo-left-exit {
            0% { opacity: 1; transform: translateX(0) scale(1); }
            30% { opacity: 0.6; transform: translateX(-10px) scale(0.97); }
            100% { opacity: 0; transform: translateX(-40px) scale(0.9); filter: blur(6px); }
        }
        @keyframes identity-logo-right-shift {
            0% { transform: translateX(0) scale(1); }
            100% { transform: translateX(-120px) scale(1); }
        }
        .identity-divider {
            animation: identity-divider-hide 0.4s ease 1.4s forwards;
        }
        @keyframes identity-divider-hide {
            0% { opacity: 1; }
            100% { opacity: 0; height: 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .identity-logo-left,
            .identity-logo-right {
                animation: none !important;
            }
            .identity-divider {
                animation: none !important;
                opacity: 1;
                height: 100%;
            }
            .identity-team-info {
                animation: none !important;
                opacity: 1;
            }
            .identity-panel-overlay {
                animation: none !important;
                background-color: transparent;
                backdrop-filter: none;
                opacity: 1;
            }
        }
    </style>
@endonce

<div
    class="identity-panel-overlay fixed inset-0 z-[999999] flex items-center justify-center"
    aria-hidden="true"
    style="top:0;left:0;right:0;bottom:0;width:100vw;height:100vh;margin:0;padding:0;">
    <div class="identity-panel-motion relative w-full max-w-4xl overflow-hidden rounded-[2.5rem] px-8 py-12">
        <div class="relative flex w-full flex-col gap-6 sm:flex-row sm:items-center sm:justify-between sm:gap-12">
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center sm:justify-start sm:gap-12">
                <img
                    src="{{ $siteLogoUrl }}"
                    alt="{{ $siteAlt }}"
                    class="identity-logo-left h-20 w-auto sm:self-start"
                    loading="lazy"
                >
                <div class="identity-divider hidden h-16 w-px bg-slate-200 sm:block" aria-hidden="true"></div>
                <img
                    src="{{ $teamLogoUrl }}"
                    alt="{{ $teamAlt }}"
                    class="identity-logo-right h-20 w-auto sm:self-start"
                    loading="lazy"
                >
            </div>
        <div class="identity-team-info text-center text-slate-800 opacity-0 sm:text-right"
            style="animation: identity-team-info-enter 0.5s ease 2.7s forwards;">
                <span class="text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">
                    {{ $teamName }}
                </span>
            </div>
        </div>
    </div>
</div>
