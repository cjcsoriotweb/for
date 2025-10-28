@extends('layouts.tutorial')

@section('title', __('Tutoriel · Politique'))

@section('header-title', __('Politique de confidentialité'))
@section('header-subtitle', __("Comprends l'objectif de cette page avant de continuer."))

@section('content')
    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Bienvenue sur la page Politique") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Cette page rassemble nos engagements envers la protection de vos données. Fais défiler pour découvrir ce qu'elle contient et comment la parcourir rapidement.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Sections clés") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Chaque bloc met en avant un aspect précis: données collectées, droits utilisateurs, contact et versions PDF téléchargeables. Tu peux utiliser la table des matières à droite pour sauter directement vers ce qui t'intéresse.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Prêt à consulter la page ?") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Tu connais maintenant la logique générale. Clique sur “J'ai compris” pour accéder à la page sans revoir ce tutoriel.") }}
            </p>

            @isset($tutorialKey)
                <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}" class="mt-8 inline-flex">
                    @csrf
                    <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                    <button type="submit" class="rounded-md bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600">
                        {{ __("J'ai compris") }}
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection

