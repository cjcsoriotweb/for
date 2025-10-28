@extends('layouts.tutorial')

@section('title', __('Tutoriel - Mon compte'))

@section('header-title', __('Espace Mon compte'))
@section('header-subtitle', __("Decouvre comment retrouver les applications auxquelles tu es affilie."))

@section('content')

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Bienvenue dans ton espace personnel") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Depuis cette page tu peux gerer tes invitations et rejoindre les organisations qui t'ont ouvert l'acces. Fais defiler pour voir ou retrouver la liste principale.") }}
            </p>
        </div>
    </div>

    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-800 px-6">
        <div class="max-w-3xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("La liste des applications affiliees est ici") }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Juste sous le bloc de bienvenue, tu retrouveras toutes les applications et organismes dans lesquels tu es affilie. Chaque carte te permet d'acceder rapidement a l'application ou de changer d'equipe si tu en as plusieurs.") }}
            </p>
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
                        {{ __("J'ai compris") }}
                    </button>
                </form>
            @endisset
        </div>
    </div>
@endsection
