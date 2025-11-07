<form method="POST" action="{{ route('register') }}" {{ $attributes }}>
    @csrf

    <div>
        <x-label for="name" value="{{ __('Nom et prénom') }}" />
        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
    </div>

    <div class="mt-4">
        <x-label for="email" value="{{ __('E-mail') }}" />
        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-800/60 dark:bg-slate-900/80 dark:shadow-none">
        <div class="flex items-start gap-3">
            <div class="mt-1 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-400/10 dark:text-indigo-300">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7a4.5 4.5 0 10-9 0v3.5M6.75 10.5h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75A1.5 1.5 0 015.25 18v-6a1.5 1.5 0 011.5-1.5z" />
                </svg>
            </div>
            <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __("Pour des raisons de sécurité, choisissez un mot de passe unique que vous n’avez jamais utilisé ailleurs.") }}
            </p>
        </div>

        <div class="mt-5 space-y-4">
            <div>
                <x-label for="password" value="{{ __('Mot de passe') }}" class="text-slate-700 dark:text-slate-200" />
                <div class="relative mt-1">
                    <x-input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="peer block w-full rounded-xl border-slate-300 bg-white/60 py-2.5 pr-12 text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-100 dark:placeholder-slate-500"
                    />
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Au moins 12 caractères. Idéalement une phrase, avec lettres + chiffres + symbole.') }}
                </p>
            </div>

            <div>
                <x-label for="password_confirmation" value="{{ __('Confirmez le mot de passe') }}" class="text-slate-700 dark:text-slate-200" />
                <x-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-1 block w-full rounded-xl border-slate-300 bg-white/60 py-2.5 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-100"
                />
            </div>
        </div>

        <ul class="mt-5 grid grid-cols-1 gap-2 text-xs text-slate-500 dark:text-slate-400 sm:grid-cols-2">
            <li class="flex items-center gap-2">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500/80"></span>
                {{ __('Évitez les mots de passe réutilisés') }}
            </li>
            <br>
            <li class="flex items-center gap-2">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500/80"></span>
                {{ __('Préférez une phrase secrète') }}
            </li>
        </ul>
    </div>

    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
            <x-label for="terms">
                <div class="flex items-center">
                    <x-checkbox name="terms" id="terms" required />

                    <div class="ms-2">
                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('guest.terms').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('guest.policy').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                        ]) !!}
                    </div>
                </div>
            </x-label>
        </div>
    @endif

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
            {{ __('Déjà inscrit ?') }}
        </a>

        <x-button class="ms-4">
            {{ __("S'inscrire") }}
        </x-button>
    </div>
</form>
