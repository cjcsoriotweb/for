<x-admin.global-layout
    icon="person_check"
    :title="__('Gestion des utilisateurs')"
    :subtitle="__('Supervisez les comptes créés et leurs rattachements aux équipes.')"
>
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form
                method="GET"
                action="{{ route('superadmin.users.index') }}"
                class="flex w-full max-w-md items-center gap-2 rounded-full bg-white/80 px-4 py-2 shadow-sm shadow-slate-900/5 ring-1 ring-slate-200 transition hover:ring-slate-300 focus-within:ring-2 focus-within:ring-indigo-500 dark:bg-slate-900/70 dark:shadow-none dark:ring-slate-700"
            >
                <span class="material-symbols-outlined text-slate-400">search</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('Rechercher un utilisateur (nom ou email)…') }}"
                    class="w-full border-none bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:border-none focus:outline-none focus-visible:outline-none dark:text-slate-200"
                />
            </form>
            @if (Route::has('users.create'))
                <a
                    href="{{ route('users.create') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-600"
                >
                    <span class="material-symbols-outlined text-base">group_add</span>
                    {{ __('Inviter un utilisateur') }}
                </a>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($users as $user)
                <article class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg shadow-slate-900/5 transition hover:border-indigo-300 hover:shadow-indigo-200/20 dark:border-slate-700/70 dark:bg-slate-900 dark:hover:border-indigo-500">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                <span class="material-symbols-outlined text-lg">person</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800/70">
                                {{ __('Équipes') }} : {{ $user->teams_count }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800/70">
                                {{ __('Équipe active') }} : {{ $user->currentTeam?->name ?? __('Non définie') }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800/70">
                                {{ __('Inscrit le') }} : {{ optional($user->created_at)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <a
                            href="{{ route('superadmin.users.show', $user) }}"
                            class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                        >
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            {{ __('Voir profil') }}
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200/70 bg-white p-8 text-center text-sm text-slate-500 dark:border-slate-700/70 dark:bg-slate-900 dark:text-slate-400">
                    {{ __("Aucun compte ne correspond à votre recherche.") }}
                </div>
            @endforelse
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
</x-admin.global-layout>
