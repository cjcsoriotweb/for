<x-app-layout>
    <!-- Full Height Container -->
    <div class="min-h-screen flex flex-col">
        <main class="flex-1 relative">
            <!-- Background gradient overlay -->

            <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div
                    class="relative mb-16 overflow-hidden rounded-3xl border border-slate-200/70 bg-white/80 p-12 text-center shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/40 dark:shadow-none">
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-indigo-500/10">
                    </div>
                    <div
                        class="pointer-events-none absolute -right-24 -top-20 h-56 w-56 rounded-full bg-blue-400/20 blur-3xl dark:bg-blue-400/10">
                    </div>
                    <div class="relative">
                        <div
                            class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg shadow-blue-500/30">
                            <span class="material-symbols-outlined text-3xl text-white">person</span>
                        </div>
                        <h1 class="mb-4 text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                            {{ __('Mon Profil') }}
                        </h1>
                        <p class="text-lg text-slate-600 dark:text-slate-300">
                            {{ __('Gérez vos informations personnelles et la sécurité de votre compte') }}
                        </p>
                    </div>
                </div>

                <!-- Profile Options Grid -->
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Changer le mot de passe -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/20">
                                <span class="material-symbols-outlined text-xl text-blue-600 dark:text-blue-400">lock</span>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Changer le mot de passe') }}
                            </h3>
                            <p class="mb-6 text-slate-600 dark:text-slate-300">
                                {{ __('Modifiez votre mot de passe pour renforcer la sécurité de votre compte.') }}
                            </p>
                            <a href="{{ route('user-password.edit') }}"
                               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Modifier') }}
                                <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <!-- Changer le nom -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/20">
                                <span class="material-symbols-outlined text-xl text-green-600 dark:text-green-400">edit</span>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Changer le nom') }}
                            </h3>
                            <p class="mb-6 text-slate-600 dark:text-slate-300">
                                {{ __('Mettez à jour vos informations personnelles.') }}
                            </p>
                            <a href="{{ route('user-profile-information.edit') }}"
                               class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                {{ __('Modifier') }}
                                <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    @php
                        use Laravel\Fortify\Fortify;

                        $twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
                        $isConfirmingTwoFactor = ! empty($user->two_factor_secret) && is_null($user->two_factor_confirmed_at);
                        $showRecoveryCodes = $twoFactorEnabled || session('status') === Fortify::RECOVERY_CODES_GENERATED || session('status') === Fortify::TWO_FACTOR_AUTHENTICATION_CONFIRMED;
                    @endphp

                    <!-- Authentification à deux facteurs -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/20">
                                <span class="material-symbols-outlined text-xl text-purple-600 dark:text-purple-400">security</span>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Sécurisation 2FA') }}
                            </h3>
                            <p class="mb-4 text-slate-600 dark:text-slate-300">
                                {{ __('Activez l\'authentification à deux facteurs pour protéger votre compte avec un second facteur de connexion.') }}
                            </p>

                            @if (session('status') === Fortify::TWO_FACTOR_AUTHENTICATION_ENABLED)
                                <div class="mb-4 rounded-2xl border border-blue-200 bg-blue-50/80 px-4 py-3 text-sm text-blue-900 dark:border-blue-400/40 dark:bg-blue-900/40 dark:text-blue-200">
                                    {{ __('Un QR code vient d\'être généré : scannez-le avec Google Authenticator puis saisissez le code à 6 chiffres pour valider.') }}
                                </div>
                            @endif

                            @if (session('status') === Fortify::TWO_FACTOR_AUTHENTICATION_CONFIRMED)
                                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-500/40 dark:bg-emerald-900/40 dark:text-emerald-200">
                                    {{ __('Authentification à deux facteurs activée.') }}
                                </div>
                            @endif

                            @if (session('status') === Fortify::TWO_FACTOR_AUTHENTICATION_DISABLED)
                                <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-600/40 dark:bg-slate-800/40 dark:text-slate-300">
                                    {{ __('Authentification à deux facteurs désactivée.') }}
                                </div>
                            @endif

                            @if (session('status') === Fortify::RECOVERY_CODES_GENERATED)
                                <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50/80 px-4 py-3 text-sm text-amber-900 dark:border-amber-500/40 dark:bg-amber-900/40 dark:text-amber-200">
                                    {{ __('De nouveaux codes de récupération ont été générés.') }}
                                </div>
                            @endif

                            @if ($isConfirmingTwoFactor)
                                <div class="mt-4 max-w-xl text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-semibold">
                                        {{ __('Scannez ce QR code avec votre application Google Authenticator ou copiez la clé de configuration et saisissez ensuite le code généré.') }}
                                    </p>
                                </div>

                                <div class="mt-4 p-2 inline-block bg-white dark:bg-slate-900 rounded-2xl shadow-inner">
                                    {!! $user->twoFactorQrCodeSvg() !!}
                                </div>

                                <div class="mt-4 max-w-xl text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-semibold">
                                        {{ __('Clé de configuration') }} :
                                        {{ Fortify::currentEncrypter()->decrypt($user->two_factor_secret) }}
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('two-factor.confirm') }}" class="mt-4 space-y-3">
                                    @csrf
                                    <div>
                                        <label for="code" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ __('Code à 6 chiffres') }}
                                        </label>
                                        <input
                                            id="code"
                                            name="code"
                                            type="text"
                                            inputmode="numeric"
                                            autocomplete="one-time-code"
                                            value="{{ old('code') }}"
                                            class="mt-2 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                        />
                                        @error('code', 'confirmTwoFactorAuthentication')
                                            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                            class="inline-flex items-center rounded-2xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                        {{ __('Confirmer') }}
                                    </button>
                                </form>
                            @endif

                            @if ($showRecoveryCodes && $twoFactorEnabled && $user->two_factor_recovery_codes)
                                <div class="mt-6 max-w-xl text-sm text-slate-600 dark:text-slate-300">
                                    <p class="font-semibold">
                                        {{ __('Conservez précieusement ces codes de récupération. Vous pourrez les utiliser si vous perdez votre appareil TOTP.') }}
                                    </p>
                                </div>

                                <div class="mt-4 grid gap-2 max-w-xl rounded-2xl bg-slate-50 p-4 text-sm font-mono text-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                                    @foreach (json_decode(Fortify::currentEncrypter()->decrypt($user->two_factor_recovery_codes), true) as $code)
                                        <div class="rounded-xl bg-white/60 px-3 py-2 dark:bg-slate-800/40">{{ $code }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                @if (! $twoFactorEnabled)
                                    <form method="POST" action="{{ route('two-factor.enable') }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                            {{ __('Activer') }}
                                            <span class="material-symbols-outlined ml-2 text-sm">arrow_forward</span>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:bg-slate-800/40 dark:text-slate-100 dark:hover:bg-slate-700">
                                            {{ __('Régénérer les codes de récupération') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('two-factor.disable') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            {{ __('Désactiver') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Compte connecté -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-blue-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/20">
                                <span class="material-symbols-outlined text-xl text-indigo-600 dark:text-indigo-400">account_circle</span>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Compte connecté') }}
                            </h3>
                            <p class="mb-6 text-slate-600 dark:text-slate-300">
                                {{ __('Informations sur votre compte actuel et statut de connexion.') }}
                            </p>
                            <div class="space-y-2">
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    <strong>{{ __('Email:') }}</strong> {{ $user->email }}
                                </div>
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    <strong>{{ __('Nom:') }}</strong> {{ $user->name }}
                                </div>
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    <strong>{{ __('Statut:') }}</strong>
                                    <span class="inline-flex items-center rounded-lg bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                        {{ __('Connecté') }}
                                        <span class="material-symbols-outlined ml-1 text-xs">check_circle</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supprimer le compte -->
                    <div class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:ring-slate-600">
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-pink-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/20">
                                <span class="material-symbols-outlined text-xl text-red-600 dark:text-red-400">delete_forever</span>
                            </div>
                            <h3 class="mb-2 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Supprimer le compte') }}
                            </h3>
                            <p class="mb-6 text-slate-600 dark:text-slate-300">
                                {{ __('Supprimez définitivement votre compte et toutes vos données.') }}
                            </p>
                            <button type="button"
                                    onclick="confirm('{{ __('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.') }}') || event.stopImmediatePropagation()"
                                    class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                {{ __('Supprimer') }}
                                <span class="material-symbols-outlined ml-2 text-sm">delete</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="mt-16">
                    <div class="rounded-2xl bg-slate-50 p-8 dark:bg-slate-800/50">
                        <h2 class="mb-4 text-2xl font-bold text-slate-900 dark:text-white">
                            {{ __('Informations supplémentaires') }}
                        </h2>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ __('Sécurité du compte') }}
                                </h3>
                                <ul class="space-y-2 text-slate-600 dark:text-slate-300">
                                    <li class="flex items-center">
                                        <span class="material-symbols-outlined mr-2 text-sm text-green-600">check_circle</span>
                                        {{ __('Mot de passe sécurisé recommandé') }}
                                    </li>
                                    <li class="flex items-center">
                                        <span class="material-symbols-outlined mr-2 text-sm {{ $user->two_factor_secret ? 'text-green-600' : 'text-yellow-600' }}">{{ $user->two_factor_secret ? 'check_circle' : 'warning' }}</span>
                                        {{ __('Authentification à deux facteurs') }}
                                    </li>
                                    <li class="flex items-center">
                                        <span class="material-symbols-outlined mr-2 text-sm text-green-600">check_circle</span>
                                        {{ __('Connexion sécurisée (HTTPS)') }}
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ __('Support') }}
                                </h3>
                                <p class="text-slate-600 dark:text-slate-300 mb-4">
                                    {{ __('Besoin d\'aide ? Contactez notre support technique.') }}
                                </p>
                                <a href="{{ route('user.tickets.create') }}"
                                   class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:bg-slate-700 dark:hover:bg-slate-600">
                                    {{ __('Contacter le support') }}
                                    <span class="material-symbols-outlined ml-2 text-sm">help</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @livewire('assistant')
</x-app-layout>
