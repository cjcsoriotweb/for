@extends('layouts.tutorial')

@section('title', __('Tutoriel - Accueil administrateur'))

@section('header-title', __('Accueil administrateur'))
@section('header-subtitle', __("Decouvre comment tirer parti du tableau de bord principal."))

@section('content')
    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        @include('tutorials.partials.admin-home-hero')
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        @include('tutorials.partials.admin-home-metrics')
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        @include('tutorials.partials.admin-home-actions')
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        @include('tutorials.partials.admin-home-indicators')
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Pret a piloter ton organisme ?") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Tu peux rejoindre la page maintenant ou y revenir plus tard depuis le menu. Ce tutoriel restera disponible en cas de besoin.") }}
            </p>

            @isset($tutorialKey)
                <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}" class="mt-8 inline-flex">
                    @csrf
                    <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                    <button type="submit" class="rounded-md bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600">
                        {{ ($forced ?? false) ? __("Continuer vers la page") : __("J'ai compris") }}
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection
