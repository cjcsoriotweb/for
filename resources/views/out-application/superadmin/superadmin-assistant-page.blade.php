@php
    $iframeUrl = config('services.goodview_genie_url');
@endphp

<x-admin.global-layout
    icon="smart_toy"
    :title="__('Assistant Superadmin')"
    :subtitle="__('Interagissez avec l’IA embarquée directement depuis l’interface Superadmin.')"
>
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-100 bg-white/80 p-8 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Assistant IA') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Dialogue instantané') }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Posez vos questions métier, récupérez des commandes ou des rapports et laissez l’assistant s’occuper des recherches.') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a
                        href="{{ $iframeUrl }}"
                        target="_blank"
                        rel="noreferrer"
                        class="inline-flex items-center justify-center rounded-2xl border border-indigo-100 bg-indigo-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        {{ __('Ouvrir dans un nouvel onglet') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white/90 shadow-inner ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70">
            <iframe
                src="{{ $iframeUrl }}"
                class="h-[70vh] w-full rounded-3xl border border-slate-100 shadow-inner dark:border-slate-800"
                loading="lazy"
                title="Assistant Superadmin"
            ></iframe>
        </section>
    </div>
</x-admin.global-layout>
