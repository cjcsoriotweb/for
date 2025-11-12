@php
    $iframeUrl =  config('services.phpmyadmin');
@endphp

<x-admin.global-layout
    icon="dns"
    :title="__('Base de données')"
    :subtitle="__('Accès rapide à la console Superadmin dans un iframe sécurisé.')"
>
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-100 bg-white/70 p-8 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Outils') }}
                    </p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Page DB interne') }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Chargez la console Superadmin directement dans cette page ou ouvrez-la dans un nouvel onglet pour les modifications lourdes.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('superadmin.db.backup') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        >
                            {{ __('Sauvegarde base de donnée') }}
                        </button>

                        <a taget="_blank" href="{{ $iframeUrl }}" 
                            class="inline-flex items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        >Ouvrir Phpmyadmin</a>
                    </form>
                </div>
            </div>
        </section>

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

    </div>
</x-admin.global-layout>
