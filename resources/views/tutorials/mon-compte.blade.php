@extends('layouts.tutorial')

@section('title', __('Tutoriel - Mon compte'))

@section('header-title', __('Espace Mon compte'))
@section('header-subtitle', __("Decouvre comment retrouver les applications auxquelles tu es affilie."))

@push('tutorial-styles')
    <style>
        .tutorial-chat-preview {
            position: relative;
        }

        .tutorial-chat-preview #chat {
            position: absolute !important;
            bottom: 1.5rem !important;
            right: 1.5rem !important;
            top: auto !important;
            left: auto !important;
        }
    </style>
@endpush

@section('content')
    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __('Bienvenue dans ton espace personnel') }}
                @if (auth()->check())
                    {{ auth()->user()->name }}
                @endif
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Depuis cette page tu peux gerer tes invitations et rejoindre les organisations qui t'ont ouvert l'acces. Fais defiler pour voir ou retrouver la liste principale.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __('Modifier votre compte') }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Une barre sera presente en haut de la page, elle restera identique a celle presente ici.") }}
            </p>
            <div class="relative mt-10 h-[300px] overflow-hidden rounded-3xl border border-slate-700/40 bg-slate-900/60 p-6">
                <x-layout.header />
            </div>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __('Un probleme ?') }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Tu peux contacter le support directement depuis Mon compte. Le bouton est accessible en bas a droite.") }}
            </p>
            <div class="tutorial-chat-preview mx-auto mt-10 h-[28rem] w-full max-w-2xl rounded-3xl border border-slate-700/40 bg-slate-900/60 p-6 shadow-xl shadow-slate-900/40">
                <div class="pointer-events-none absolute inset-0 rounded-3xl border border-slate-700/30"></div>
                <div class="relative flex h-full flex-col justify-between">
                    <div class="space-y-4 text-left">
                        <div class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                            {{ __('Support disponible') }}
                        </div>
                        <p class="text-sm text-slate-300">
                            {{ __("Clique sur le bouton rond pour ouvrir la conversation avec l'equipe. Une fois ouvert, tu peux envoyer un message ou consulter l'historique de tes tickets.") }}
                        </p>
                    </div>
                    <livewire:support.chat-widget />
                </div>
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
