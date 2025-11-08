<x-app-layout>
    <div class="min-h-screen bg-slate-50/30 dark:bg-slate-900/50">
        <main class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/80 px-6 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/60 dark:shadow-none">
                    <div class="mb-10 space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Profil') }}</p>
                        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Modifier mes informations') }}</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __('Mettez à jour votre nom, votre adresse e-mail et votre photo de profil pour garder votre compte à jour.') }}
                        </p>
                    </div>

                    @if (session('status') === 'profile-information-updated')
                        <div class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/50 dark:bg-emerald-900/40 dark:text-emerald-200">
                            {{ __('Les informations de votre profil ont été enregistrées.') }}
                        </div>
                    @endif

                    @if ($errors->updateProfileInformation->any())
                        <div class="mb-6 rounded-2xl border border-red-200/80 bg-red-50/80 px-4 py-3 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-900/40 dark:text-red-200">
                            <ul class="space-y-1">
                                @foreach ($errors->updateProfileInformation->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="name">
                                {{ __('Nom complet') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="email">
                                {{ __('Adresse e-mail') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:ring-blue-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300" for="photo">
                                {{ __('Photo de profil (optionnelle)') }}
                            </label>
                            <div class="mt-2">
                                <input
                                    id="photo"
                                    name="photo"
                                    type="file"
                                    accept="image/jpeg,image/png"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-600 transition placeholder:text-slate-400 dark:text-slate-300 dark:file:bg-blue-900/30 dark:file:text-blue-300"
                                />
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Formats acceptés : jpg, jpeg, png (max 1 Mo).') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('user.profile') }}"
                               class="text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                                {{ __('Retour au profil') }}
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
