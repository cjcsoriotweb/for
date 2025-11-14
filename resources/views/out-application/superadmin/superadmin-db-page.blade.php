@php
    $iframeUrl = config('services.phpmyadmin');
    $backups = $backups ?? collect();
    $backupsCount = $backups->count();
    $latestBackup = $backups->first();
@endphp

<x-admin.global-layout
    icon="dns"
    :title="__('Base de données')"
    :subtitle="__('Accès rapide à la console Superadmin dans un iframe sécurisé.')"
>
    <div class="space-y-8">
        <section class="overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900/80 to-slate-900/60 px-8 py-10 shadow-2xl shadow-slate-900/40 ring ring-white/5">
            <div class="flex flex-col gap-6 lg:flex-row lg:gap-12">
                <div class="flex-1 space-y-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400">
                        {{ __('Outils') }}
                    </p>
                    <h1 class="text-4xl font-semibold text-white">
                        {{ __('Console Base de données') }}
                    </h1>
                    <p class="text-base text-slate-200">
                        {{ __('Chargez la console Superadmin en toute sécurité, surveillez vos sauvegardes et agissez sur vos bases de données depuis une interface centralisée sans quitter le tableau de bord.') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('superadmin.db.backup') }}" class="inline-flex">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white px-6 py-3 text-xs font-semibold uppercase tracking-wide text-slate-900 shadow-lg shadow-slate-900/30 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                            >
                                {{ __('Créer une sauvegarde') }}
                            </button>
                        </form>
                        <a
                            target="_blank"
                            href="{{ $iframeUrl }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-indigo-400/40 bg-indigo-500 px-6 py-3 text-xs font-semibold uppercase tracking-wide text-white shadow-lg shadow-indigo-500/30 transition hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400"
                        >
                            {{ __('Ouvrir phpMyAdmin') }}
                        </a>
                    </div>
                </div>
                <div class="flex flex-1 flex-col gap-4 rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-300">
                        {{ __('Statut rapide') }}
                    </p>
                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                            <dt class="text-xs font-semibold text-slate-300">{{ __('Copies de sécurité') }}</dt>
                            <dd class="mt-2 text-2xl font-semibold text-white">{{ $backupsCount }}</dd>
                            <p class="text-xs text-slate-400">
                                {{ $latestBackup
                                    ? __('Dernière : :date', [
                                        'date' => $latestBackup['modified_at']
                                            ->locale(app()->getLocale())
                                            ->isoFormat('LLL'),
                                    ])
                                    : __('Aucune sauvegarde enregistrée')
                                }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                            <dt class="text-xs font-semibold text-slate-300">{{ __('Serveurs actifs') }}</dt>
                            <dd class="mt-2 text-2xl font-semibold text-white">1</dd>
                            <p class="text-xs text-slate-400">{{ __('Cluster principal') }}</p>
                        </div>
                    </dl>
                    <p class="text-xs text-slate-400">
                        {{ __('Les informations ci-dessus sont mises à jour lors de chaque sauvegarde ou opération critique.') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white/70 p-8 shadow-xl ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Workflow') }}
                    </p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Guide rapide') }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Nouvelle sauvegarde, exploration de la base et restauration : tout est accessible depuis cet espace sécurisé.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Sauvegardes régulières') }}
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Accès sécurisé') }}
                    </div>
                </div>
            </div>
        </section>

        @if (session('status'))
            <section class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-xl ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80 dark:ring-white/5">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm text-slate-600 dark:text-slate-200">
                        {{ session('status') }}
                    </p>
                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:border-slate-700 dark:text-slate-400">
                        {{ __('Info') }}
                    </span>
                </div>
            </section>
        @endif

        @if (session('backup_file'))
            <section class="rounded-3xl border border-emerald-200 bg-emerald-50/80 p-6 shadow-inner ring-1 ring-emerald-300/30 dark:border-emerald-500/40 dark:bg-emerald-900/70">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <p class="text-sm font-medium text-emerald-700 dark:text-emerald-200">
                        {{ __('Sauvegarde prête : :file', ['file' => session('backup_file')]) }}
                    </p>
                    <a
                        href="{{ route('superadmin.db.backup.download', session('backup_file')) }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500"
                    >
                        {{ __('Télécharger') }}
                    </a>
                </div>
            </section>
        @endif

        <section class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-xl ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80 dark:ring-white/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Archives') }}
                    </p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Sauvegardes disponibles') }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Retrouvez l’historique des exports effectués sur la base et récupérez le fichier qui vous intéresse.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Total : :count', ['count' => $backupsCount]) }}
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ $latestBackup
                            ? __('Dernier : :date', [
                                'date' => $latestBackup['modified_at']
                                    ->locale(app()->getLocale())
                                    ->isoFormat('LLL'),
                            ])
                            : __('Aucune sauvegarde récente')
                        }}
                    </div>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900/60">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-900/50 dark:text-slate-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('Fichier') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Taille') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Modifié le') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white text-slate-600 dark:bg-slate-900/80 dark:text-slate-300">
                        @forelse ($backups as $backup)
                            <tr class="border-t border-slate-100 dark:border-slate-800">
                                <td class="px-4 py-4 text-base font-medium text-slate-900 dark:text-white">
                                    {{ $backup['name'] }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $backup['human_size'] }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $backup['modified_at']
                                        ->locale(app()->getLocale())
                                        ->isoFormat('LLL') }}
                                </td>
                                <td class="px-4 py-4">
                                    <a
                                        href="{{ route('superadmin.db.backup.download', $backup['name']) }}"
                                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-900/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:border-slate-500 dark:hover:bg-slate-800"
                                    >
                                        {{ __('Télécharger') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Aucune sauvegarde trouvée. Lancez une nouvelle sauvegarde pour voir les fichiers ici.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-xl ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80 dark:ring-white/5">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Console Superadmin dans un iframe sécurisé') }}
                    </h2>
                    <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
                        {{ __('Données protégées') }}
                    </span>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Lancez phpMyAdmin directement dans l’encadré ci-dessous ou utilisez le bouton d’ouverture pour travailler dans un nouvel onglet lorsque vous avez besoin de plus de place.') }}
                </p>
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-inner dark:border-slate-700 dark:bg-slate-900">
                    <iframe
                        src="{{ $iframeUrl }}"
                        class="min-h-[480px] w-full bg-white dark:bg-slate-800"
                        loading="lazy"
                        title="{{ __('Console Superadmin') }}"
                    ></iframe>
                </div>
            </div>
        </section>

    </div>
</x-admin.global-layout>
