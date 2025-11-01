@extends('layouts.tutorial')

@section('title', __('Tutoriel - Mon compte'))

@section('header-title', __('Votre compte'))
@section('header-subtitle', __("Découvrez comment fonctionne votre espace personnel"))

@section('content')
<div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -right-32 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(59,130,246,0.06),transparent_55%)]"></div>
    </div>

    <div class="relative mx-auto max-w-3xl px-6 text-center">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-sm">
            @if (auth()->check())
            <span class="mx-auto mb-5 inline-flex items-center gap-2 rounded-full bg-blue-400/10 px-4 py-2
                              text-sm font-medium text-blue-300 ring-1 ring-inset ring-blue-400/30">
                <!-- icône user -->
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 21a8 8 0 10-16 0m8-10a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                {{ auth()->user()->name }}
            </span>
            @endif

            <h1 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                {{ __("C'est la première fois que vous venez ici ?") }}
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-slate-300">
                {{ __("Depuis cette page, vous pouvez gérer vos invitations et rejoindre les organisations qui vous ont ouvert l’accès. Faites défiler pour voir ou retrouver la liste principale.") }}
            </p>
        </div>
    </div>
</div>

<div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -right-32 h-96 w-96 rounded-full bg-purple-500/20 blur-3xl"></div>
        <div class="absolute -top-32 -left-24 h-96 w-96 rounded-full bg-purple-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(59,130,246,0.06),transparent_55%)]"></div>
    </div>

        <!-- Badge titre / contexte -->
        <span class="mx-auto mb-4 inline-flex items-center gap-2 rounded-full bg-indigo-400/10 px-4 py-1.5
                      text-xs font-medium text-indigo-300 ring-1 ring-inset ring-indigo-400/30">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 15v.01M8.5 11.5a3.5 3.5 0 107 0c0-2.5-3.5-4-3.5-6.5M4 15.5c1.5 2.5 4.5 4 8 4s6.5-1.5 8-4"/>
            </svg>
            {{ __('Profil & Sécurité') }}
        </span>

        <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">
            {{ __('Modifier votre compte') }}
        </h2>


        <p class="mt-4 text-base text-slate-300">
            {{ __("Une barre sera presente en haut de la page, elle restera identique a celle presente ici.") }}
        </p>
        <div class="relative mt-10 h-[300px] overflow-hidden rounded-3xl p-6">
            <x-layout.header />
        </div>
    </div>
</div>



<div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-24 -right-32 h-96 w-96 rounded-full bg-purple-500/20 blur-3xl"></div>
        <div class="absolute -top-32 -left-24 h-96 w-96 rounded-full bg-purple-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(59,130,246,0.06),transparent_55%)]"></div>
    </div>

<div class="max-w-3xl text-center">
        <h2 class="text-3xl font-semibold">
            {{ __('Un probleme ?') }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Tu peux contacter le support directement depuis Mon compte. Le bouton est accessible en bas a droite.") }}
        </p>
        <div class="mx-auto mt-10 w-full max-w-2xl rounded-3xl border border-slate-700/40 bg-slate-900/60 p-6 text-left shadow-xl shadow-slate-900/40">
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                {{ __('Support disponible') }}
            </div>
            <p class="mt-4 text-sm text-slate-300">
                {{ __("Depuis le dock, ouvre l'onglet Support pour discuter avec l'equipe ou consulter tes tickets. Tout reste accessible en bas de l'ecran.") }}
            </p>
        </div>
    </div>
</div>

<div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
    <div class="max-w-3xl text-center">
        <h2 class="text-3xl font-semibold">
            {{ __("Besoin d'y revenir plus tard ?") }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Tu peux maintenant consulter ta page \"Mon compte\". Si tu connais deja son fonctionnement, clique sur le bouton ci-dessous pour la rejoindre immediatement.") }}
        </p>

        @isset($tutorialKey)
        <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}" class="mt-8 inline-flex">
            @csrf
            <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
            <button type="submit" class="rounded-md bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600">
                {{ ($forced ?? false) ? __("Continuer vers Mon compte") : __("J'ai compris") }}
            </button>
        </form>
        @endisset
    </div>
</div>
@endsection
