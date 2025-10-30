@php
    $testsCollection = collect($tests ?? []);
@endphp

<x-admin.global-layout
    icon="science"
    :title="__('Tests superadmin')"
    :subtitle="__('Detecte automatiquement les tests du dossier dedie et execute-les sequentiellement.')"
>
    <div
        x-data="(() => {
            const initial = @js($testsCollection);

            return {
                tests: initial.map((test) => ({
                    ...test,
                    status: 'idle',
                    output: '',
                    duration: null,
                })),
                runUrl: '{{ route('superadmin.tests.run') }}',
                state: 'idle',

                indicatorClass() {
                    const classes = {
                        idle: 'bg-slate-400',
                        running: 'bg-amber-400',
                        passed: 'bg-emerald-500',
                        failed: 'bg-rose-500',
                    };

                    return classes[this.state] ?? 'bg-slate-400';
                },

                resetTests() {
                    this.tests = this.tests.map((test) => ({
                        ...test,
                        status: 'idle',
                        output: '',
                        duration: null,
                    }));
                },

                badgeClass(status) {
                    const classes = {
                        idle: 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200',
                        running: 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200',
                        passed: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200',
                        failed: 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200',
                    };

                    return classes[status] ?? 'bg-slate-200 text-slate-700';
                },

                badgeLabel(status) {
                    const labels = {
                        idle: '{{ __('En attente') }}',
                        running: '{{ __('En cours') }}',
                        passed: '{{ __('Reussi') }}',
                        failed: '{{ __('Echec') }}',
                    };

                    return labels[status] ?? '{{ __('En attente') }}';
                },

                durationText(value) {
                    return `${Number(value ?? 0).toFixed(2)}s`;
                },

                async runAll() {
                    this.state = 'running';
                    this.resetTests();

                    try {
                        const response = await fetch(this.runUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({}),
                        });

                        if (!response.ok) {
                            throw new Error('HTTP error');
                        }

                        const payload = await response.json();

                        if (Array.isArray(payload.tests)) {
                            this.tests = this.tests.map((test) => {
                                const match = payload.tests.find((result) => result.path === test.path);

                                return match
                                    ? { ...test, ...match, status: match.status ?? 'failed' }
                                    : test;
                            });
                        }

                        this.state = payload.status ?? 'passed';
                    } catch (error) {
                        console.error(error);
                        this.state = 'failed';
                    } finally {
                        if (this.state === 'running') {
                            this.state = 'failed';
                        }
                    }
                },
            };
        })()"
        class="space-y-8"
    >
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200/70 bg-white p-8 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Batterie de tests superadmin') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                        {{ __('Cliquez sur Tout joue pour lancer les tests unitaires du dossier configure. Les tests sont executes lun apres lautre afin de conserver un diagnostic clair.') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full text-white transition"
                        :class="indicatorClass()"
                    >
                        <span class="material-symbols-outlined text-2xl">
                            {{ 'light_mode' }}
                        </span>
                    </span>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-60"
                        :class="(state === 'running' || tests.length === 0) ? 'pointer-events-none' : 'cursor-pointer'"
                        @click="runAll"
                        :disabled="state === 'running' || tests.length === 0"
                    >
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                        <span>{{ __('Tout joue') }}</span>
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-slate-200 p-6 dark:border-slate-700" x-show="tests.length === 0">
                <p class="text-sm text-slate-500 dark:text-slate-300">
                    {{ __('Aucun test na ete detecte. Ajoutez des fichiers PHP dans le dossier configure pour les afficher ici.') }}
                </p>
            </div>

            <div class="space-y-4" x-show="tests.length > 0">
                <template x-for="test in tests" :key="test.path">
                    <div class="rounded-2xl border border-slate-200/70 bg-slate-50 p-5 dark:border-slate-700/70 dark:bg-slate-800/70">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white" x-text="test.name"></h3>
                                <p class="text-xs font-mono text-slate-500 dark:text-slate-300" x-text="test.path"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-widest"
                                    :class="badgeClass(test.status)"
                                    x-text="badgeLabel(test.status)"
                                ></span>
                                <span class="text-xs text-slate-500 dark:text-slate-300" x-show="test.duration">
                                    <span x-text="durationText(test.duration)"></span>
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl bg-black/80 p-4 text-xs text-slate-100" x-show="test.output">
                            <pre class="whitespace-pre-wrap" x-text="test.output"></pre>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-admin.global-layout>

