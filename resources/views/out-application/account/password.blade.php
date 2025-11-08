<x-app-layout>
    <div class="min-h-screen bg-slate-50/30 dark:bg-slate-900/50">
        <main class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/80 px-6 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/60 dark:shadow-none">
                    <div class="mb-10 space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Sécurité') }}</p>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Changer le mot de passe') }}</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __('Modifiez votre mot de passe pour renforcer la sécurité de votre compte.') }}
                        </p>
                    </div>

                    @if (session('status') === 'password-updated')
                        <div class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/50 dark:bg-emerald-900/40 dark:text-emerald-200">
                            {{ __('Votre mot de passe a été mis à jour.') }}
                        </div>
                    @endif

                    @if ($errors->updatePassword->any())
                        <div class="mb-6 rounded-2xl border border-red-200/80 bg-red-50/80 px-4 py-3 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-900/40 dark:text-red-200">
                            <ul class="space-y-1">
                                @foreach ($errors->updatePassword->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user-password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('Mot de passe actuel') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('Confirmation du mot de passe') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('user.profile') }}"
                               class="text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                                {{ __('Retour au profil') }}
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Mettre à jour') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
