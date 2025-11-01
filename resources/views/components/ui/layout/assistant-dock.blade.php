@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    if (! $enable || ! auth()->check()) {
        return;
    }

    $slotMeta = [
        'chatia' => [
            'icon' => 'chat',
            'label' => __('Assistant'),
            'title' => __('Assistant IA'),
            'description' => __('Discutez avec votre assistant Evolubat pour obtenir de l aide immediatement.'),
            'iconBg' => 'bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-200',
            'panelClass' => 'border-blue-100/80 shadow-blue-500/20 dark:border-blue-500/30',
        ],
        'tutor' => [
            'icon' => 'tutor',
            'label' => __('Professeur'),
            'title' => __('Professeur virtuel'),
            'description' => __('Bientot disponible pour un suivi personnalise de vos apprenants.'),
            'iconBg' => 'bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-200',
            'panelClass' => 'border-amber-100/80 shadow-amber-500/15 dark:border-amber-500/30',
        ],
        'support' => [
            'icon' => 'support',
            'label' => __('Support'),
            'title' => __('Centre support'),
            'description' => __('Signalez un bug et suivez vos tickets sans quitter la page.'),
            'iconBg' => 'bg-sky-500/10 text-sky-600 dark:bg-sky-500/20 dark:text-sky-200',
            'panelClass' => 'border-sky-100/80 shadow-sky-500/15 dark:border-sky-500/30',
        ],
        'search' => [
            'icon' => 'search',
            'label' => __('Recherche'),
            'title' => __('Recherche avancee'),
            'description' => __('Bientot une recherche intelligente sur toutes vos ressources.'),
            'iconBg' => 'bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200',
            'panelClass' => 'border-indigo-100/80 shadow-indigo-500/15 dark:border-indigo-500/30',
        ],
    ];

    $componentConfig = [
        'notifications' => (int) $notifications,
        'locked' => (bool) $locked,
        'initialSlot' => 'chatia',
        'slots' => $slotMeta,
    ];
@endphp

<div
    x-data="assistantDock(@js($componentConfig))"
    x-init="init()"
    @keydown.window.escape="close()"
    class="fixed bottom-6 left-1/2 z-[1200] flex w-full max-w-[520px] -translate-x-1/2 justify-center px-4 sm:bottom-8 sm:left-auto sm:right-8 sm:max-w-none sm:translate-x-0 sm:px-0"
>
    <div class="flex items-center gap-3 rounded-3xl bg-white/80 p-2 shadow-lg shadow-slate-900/10 backdrop-blur dark:bg-slate-900/80 dark:shadow-black/40">
        <button
            type="button"
            class="relative flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 dark:bg-slate-800"
            :class="activeSlot === 'chatia' ? 'ring-2 ring-blue-400/70 scale-105' : ''"
            @click="toggle('chatia')"
            :aria-pressed="activeSlot === 'chatia'"
            aria-label="{{ __('Assistant IA') }}"
        >
            <template x-if="badge('chatia')">
                <span class="absolute -top-1 -right-1 flex min-w-[1.35rem] items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-semibold leading-5 text-white shadow ring-2 ring-white dark:ring-slate-900" x-text="badge('chatia')"></span>
            </template>
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 8h10"></path>
                <path d="M7 12h6"></path>
                <path d="M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z"></path>
            </svg>
        </button>

        <button
            type="button"
            class="relative flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500"
            :class="activeSlot === 'tutor' ? 'ring-2 ring-amber-300/80 scale-105' : ''"
            @click="toggle('tutor')"
            :aria-pressed="activeSlot === 'tutor'"
            aria-label="{{ __('Professeur virtuel') }}"
        >
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                <path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path>
                <path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path>
                <path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
            </svg>
        </button>

        <button
            type="button"
            class="relative flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500"
            :class="activeSlot === 'support' ? 'ring-2 ring-sky-300/80 scale-105' : ''"
            @click="toggle('support')"
            :aria-pressed="activeSlot === 'support'"
            aria-label="{{ __('Support technique') }}"
        >
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 12v-1"></path>
                <path d="M14 12v-1"></path>
                <path d="M12 6a5 5 0 0 0-5 5v1a7 7 0 0 0 4 6v1a1 1 0 0 0 2 0v-1a7 7 0 0 0 4-6v-1a5 5 0 0 0-5-5z"></path>
                <path d="M7 10H5"></path>
                <path d="M19 10h-2"></path>
                <path d="M7 16l-2 2"></path>
                <path d="M19 16l2 2"></path>
                <path d="M7 4l1.5 1.5"></path>
                <path d="M17 4 15.5 5.5"></path>
            </svg>
        </button>

        <button
            type="button"
            class="relative hidden h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500 text-white shadow-md transition duration-150 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 sm:flex"
            :class="activeSlot === 'search' ? 'ring-2 ring-indigo-300/80 scale-105' : ''"
            @click="toggle('search')"
            :aria-pressed="activeSlot === 'search'"
            aria-label="{{ __('Recherche avancee') }}"
        >
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
        </button>
    </div>

    <div
        x-cloak
        x-show="isOpen && !locked"
        x-transition.opacity
        class="fixed inset-0 z-[1190] bg-slate-950/60 backdrop-blur-sm"
        @click="close()"
    ></div>

    <div
        x-cloak
        x-show="isOpen"
        x-transition
        class="fixed inset-0 z-[1191] flex w-full items-center justify-center px-3 py-4 sm:px-6 sm:py-8"
        :class="locked ? 'pointer-events-none' : ''"
    >
        <div
            role="dialog"
            aria-modal="true"
            :aria-labelledby="'assistant-dock-title'"
            class="pointer-events-auto relative flex h-[80vh] max-h-[740px] min-h-[60vh] w-full flex-col rounded-3xl border border-slate-200/70 bg-white/95 text-slate-900 shadow-2xl shadow-slate-900/20 dark:border-slate-700/60 dark:bg-slate-900/95 dark:text-slate-100 sm:h-[76vh] sm:max-w-5xl"
            :class="slots[activeSlot]?.panelClass || ''"
        >
            <div class="flex items-start justify-between gap-3 border-b border-slate-200/80 px-6 py-4 dark:border-slate-700/60">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl" :class="slots[activeSlot]?.iconBg">
                        <template x-if="slots[activeSlot]?.icon === 'chat'">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 8h10"></path>
                                <path d="M7 12h6"></path>
                                <path d="M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z"></path>
                            </svg>
                        </template>
                        <template x-if="slots[activeSlot]?.icon === 'tutor'">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                                <path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path>
                                <path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path>
                                <path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
                            </svg>
                        </template>
                        <template x-if="slots[activeSlot]?.icon === 'support'">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 12v-1"></path>
                                <path d="M14 12v-1"></path>
                                <path d="M12 6a5 5 0 0 0-5 5v1a7 7 0 0 0 4 6v1a1 1 0 0 0 2 0v-1a7 7 0 0 0 4-6v-1a5 5 0 0 0-5-5z"></path>
                                <path d="M7 10H5"></path>
                                <path d="M19 10h-2"></path>
                                <path d="M7 16l-2 2"></path>
                                <path d="M19 16l2 2"></path>
                                <path d="M7 4l1.5 1.5"></path>
                                <path d="M17 4 15.5 5.5"></path>
                            </svg>
                        </template>
                        <template x-if="slots[activeSlot]?.icon === 'search'">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                        </template>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.32em] text-slate-400" x-text="slots[activeSlot]?.label || ''"></p>
                        <h2 id="assistant-dock-title" class="mt-1 text-xl font-semibold text-slate-900 dark:text-white" x-text="slots[activeSlot]?.title || ''"></h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-300" x-text="slots[activeSlot]?.description || ''"></p>
                    </div>
                </div>
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-white/70 text-slate-500 transition hover:bg-white hover:text-slate-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 dark:bg-slate-800/70 dark:text-slate-300 dark:hover:bg-slate-700"
                    @click="close()"
                    x-show="!locked"
                    aria-label="{{ __('Fermer le dock') }}"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <div
                    x-cloak
                    x-show="activeSlot === 'chatia'"
                    x-transition.opacity.duration.150ms
                    class="h-full"
                >
                    <div class="mx-auto flex h-full w-full max-w-4xl">
                        @livewire('chat-box', [
                            'trainer' => 'default',
                            'title' => __('Assistant IA'),
                            'isOpen' => true,
                        ], key('dock-chat-box'))
                    </div>
                </div>

                <div
                    x-cloak
                    x-show="activeSlot === 'tutor'"
                    x-transition.opacity.duration.150ms
                    class="h-full overflow-y-auto bg-gradient-to-b from-amber-50 via-amber-50/60 to-amber-100/60 p-6 dark:from-slate-900 dark:via-slate-900/60 dark:to-slate-950"
                >
                    <div class="mx-auto max-w-3xl rounded-3xl border border-amber-200/70 bg-white/95 p-8 shadow-2xl shadow-amber-500/10 dark:border-amber-500/40 dark:bg-slate-900/90">
                        <div class="text-center">
                            <svg class="mx-auto mb-6 h-16 w-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                                <path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path>
                                <path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path>
                                <path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
                            </svg>
                            <h3 class="text-2xl font-semibold text-amber-600 dark:text-amber-200">{{ __('Professeur virtuel') }}</h3>
                            <p class="mt-3 text-sm text-amber-700/80 dark:text-amber-100/80">
                                {{ __('Nous finalisons un accompagnement pedagogique sur mesure. Restez connecte, les premieres fonctionnalites arrivent tres bientot.') }}
                            </p>
                        </div>
                        <div class="mt-6 grid gap-4 rounded-2xl bg-amber-500/10 p-6 text-sm text-amber-900/80 dark:bg-amber-500/10 dark:text-amber-100/90">
                            <p class="font-medium">{{ __('Au programme prochainement') }}</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    <span>{{ __('Plan de revision personnalise selon le profil de l apprenant.') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    <span>{{ __('Suivi des progres avec recommandations automatiques.') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    <span>{{ __('Coaching instantane base sur les formations suivies.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div
                    x-cloak
                    x-show="activeSlot === 'support'"
                    x-transition.opacity.duration.150ms
                    class="h-full"
                >
                    <div class="mx-auto h-full w-full max-w-4xl">
                        @livewire('support.ticket-reporter', [
                            'originPath' => request()->fullUrl(),
                            'originLabel' => __('Dock Signaler un bug'),
                        ], key('dock-support'))
                    </div>
                </div>

                <div
                    x-cloak
                    x-show="activeSlot === 'search'"
                    x-transition.opacity.duration.150ms
                    class="h-full overflow-y-auto bg-gradient-to-b from-indigo-50 via-indigo-50/60 to-white p-6 dark:from-slate-900 dark:via-slate-900/70 dark:to-slate-950"
                >
                    <div class="mx-auto max-w-3xl rounded-3xl border border-indigo-200/60 bg-white/95 p-8 shadow-2xl shadow-indigo-500/10 dark:border-indigo-500/40 dark:bg-slate-900/90">
                        <div class="text-center">
                            <svg class="mx-auto mb-6 h-16 w-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="M21 21l-4.35-4.35"></path>
                            </svg>
                            <h3 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-200">{{ __('Recherche avancee') }}</h3>
                            <p class="mt-3 text-sm text-indigo-700/80 dark:text-indigo-100/80">
                                {{ __('La recherche globale arrive bientot. Filtrez formations, resources et conversations en un instant.') }}
                            </p>
                        </div>
                        <div class="mt-6 space-y-3 rounded-2xl border border-indigo-200/70 bg-indigo-500/10 p-6 text-sm text-indigo-900/80 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-100/90">
                            <p class="font-medium">{{ __('Fonctionnalites prevues') }}</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                    <span>{{ __('Recherche multi-criteres et suggestions intelligentes.') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                    <span>{{ __('Historique rapide pour reprendre vos dernieres actions.') }}</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                    <span>{{ __('Integration avec l assistant IA pour contextualiser les resultats.') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof window.assistantDock !== 'function') {
        window.assistantDock = function (config = {}) {
            const scrollLock = {
                count: 0,
                lock() {
                    this.count += 1;
                    document.documentElement.classList.add('overflow-hidden');
                    document.body.classList.add('overflow-hidden');
                },
                unlock() {
                    this.count = Math.max(0, this.count - 1);
                    if (this.count === 0) {
                        document.documentElement.classList.remove('overflow-hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                },
            };

            return {
                slots: config.slots || {},
                locked: Boolean(config.locked),
                notifications: Number(config.notifications || 0),
                isOpen: Boolean(config.locked),
                activeSlot: config.locked ? (config.initialSlot || 'chatia') : null,
                toggle(slot) {
                    if (this.locked) {
                        this.activeSlot = slot;
                        this.isOpen = true;
                        return;
                    }
                    if (this.isOpen && this.activeSlot === slot) {
                        this.close();
                    } else {
                        this.open(slot);
                    }
                },
                open(slot) {
                    if (! this.slots[slot]) {
                        return;
                    }
                    this.activeSlot = slot;
                    this.isOpen = true;
                },
                close() {
                    if (this.locked) {
                        return;
                    }
                    this.isOpen = false;
                    this.activeSlot = null;
                },
                badge(slot) {
                    if (slot !== 'chatia') {
                        return null;
                    }
                    if (! this.notifications || this.notifications < 1) {
                        return null;
                    }
                    return this.notifications > 9 ? '9+' : String(this.notifications);
                },
                init() {
                    if (this.locked && ! this.activeSlot) {
                        this.activeSlot = config.initialSlot || 'chatia';
                    }

                    this.$watch('isOpen', (value) => {
                        if (value) {
                            scrollLock.lock();
                        } else {
                            scrollLock.unlock();
                        }
                    });

                    window.addEventListener('dock:open-slot', (event) => {
                        const slot = event?.detail?.slot;
                        if (slot && this.slots[slot]) {
                            this.open(slot);
                        }
                    });
                },
            };
        };
    }
</script>
