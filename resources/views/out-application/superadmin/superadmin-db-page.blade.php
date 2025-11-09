@php
    $iframeUrl = 'https://goodview.fr/db';
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
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white/70 p-4 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900">
                <iframe
                    id="db-iframe"
                    src="{{ $iframeUrl }}"
                    class="h-[80vh] w-full min-h-[560px] border-0"
                    loading="lazy"
                ></iframe>
            </div>
        </section>
    </div>
</x-admin.global-layout>
