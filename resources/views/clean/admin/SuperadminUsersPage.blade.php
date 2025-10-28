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
                class="flex w-full max-w-md items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm dark:border-slate-700 dark:bg-slate-900/70"
            >
                <span class="material-symbols-outlined text-slate-400">search</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('Rechercher un utilisateur (nom ou email)…') }}"
                    class="w-full bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none dark:text-slate-200"
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

        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">{{ __('Utilisateur') }}</th>
                        <th class="px-6 py-4">{{ __('Email') }}</th>
                        <th class="px-6 py-4 text-center">{{ __('Équipes') }}</th>
                        <th class="px-6 py-4">{{ __('Équipe active') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Inscrit le') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/80">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/60">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                    {{ $user->teams_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $user->currentTeam?->name ?? __('Non définie') }}
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-slate-500 dark:text-slate-400">
                                {{ optional($user->created_at)->translatedFormat('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __("Aucun compte ne correspond à votre recherche.") }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
</x-admin.global-layout>
