@extends('layouts.tutorial')

@section('title', __('Tutoriel - Inscription'))

@section('header-title', __('Avant de creer ton compte'))
@section('header-subtitle', __("Merci de lire ces informations importantes avant de poursuivre l'inscription."))

@section('content')
    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Pourquoi cette etape est obligatoire ?") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Nous accompagnons des organismes de formation et devons verifier certains criteres pour securiser les acces. Cette lecture rapide t'explique ce qui va t'etre demande lors de l'inscription.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Ce que tu vas devoir fournir") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Prepare une adresse email valide, un mot de passe solide et tes informations d'organisme si tu t'inscris en tant que professionnel. Tu pourras egalement accepter nos conditions legales.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Pret a commencer l'inscription ?") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Clique sur le bouton ci-dessous pour acceder au formulaire. Tu pourras toujours revenir a cette page si besoin.") }}
            </p>

            @isset($tutorialKey)
                <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}" class="mt-8 inline-flex">
                    @csrf
                    <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                    <button type="submit" class="rounded-md bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600">
                        {{ __("Continuer vers l'inscription") }}
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection

