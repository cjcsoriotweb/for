@extends('layouts.tutorial')

@section('title', __('Tutoriel - Transition'))

@php
    $tutorialLabel = \Illuminate\Support\Str::of($tutorialKey ?? '')->replace(['-', '_'], ' ')->title();
@endphp

@section('header-title', __('Preparation du tutoriel'))
@section('header-subtitle', __("Dans 10 secondes, le tutoriel demarrera automatiquement. Tu peux le lancer maintenant ou l'ignorer."))

@section('content')
    <div class="section flex min-h-screen flex-col items-center justify-center bg-slate-900 px-6">
        <div class="max-w-2xl text-center">
            <h2 class="text-3xl font-semibold">
                {{ __("Tutoriel pour :name", ['name' => $tutorialLabel]) }}
            </h2>
            <p class="mt-4 text-base text-slate-300">
                {{ __("Nous allons afficher un guide pour t'aider a prendre en main cette page. Sans action de ta part, il demarrera dans") }}
                <span id="tutorial-countdown" class="font-semibold text-emerald-400">10</span>
                {{ __("secondes.") }}
            </p>
            <p class="mt-4 text-sm text-slate-400">
                {{ __("Tu n'es pas oblige de le lire : passe-le si tu connais deja le fonctionnement.") }}
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ $tutorialUrl }}" id="start-tutorial" class="rounded-md bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-600" data-target="{{ $tutorialUrl }}">
                    {{ __("Lancer maintenant") }}
                </a>
                @isset($tutorialKey)
                    <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}">
                        @csrf
                        <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                        <button type="submit" class="rounded-md border border-slate-600 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                            {{ __("Ignorer le tutoriel") }}
                        </button>
                    </form>
                @endisset
            </div>
        </div>
    </div>
@endsection

@push('tutorial-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var startButton = document.getElementById('start-tutorial');
            if (!startButton) {
                return;
            }

            var countdownElement = document.getElementById('tutorial-countdown');
            var secondsRemaining = 10;
            var targetUrl = startButton.getAttribute('data-target');
            var timer = setInterval(function () {
                secondsRemaining--;
                if (secondsRemaining <= 0) {
                    clearInterval(timer);
                    window.location.href = targetUrl;
                    return;
                }

                if (countdownElement) {
                    countdownElement.textContent = secondsRemaining;
                }
            }, 1000);

            startButton.addEventListener('click', function (event) {
                event.preventDefault();
                clearInterval(timer);
                window.location.href = targetUrl;
            });
        });
    </script>
@endpush
