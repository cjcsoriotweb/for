<div class="py-8 space-y-8">
    <!-- Hero section -->
    <div
        class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white"
    >
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">
                    Bienvenue sur votre panneau d'administration
                </h1>
                <p class="text-blue-100 text-lg mb-6">
                    Gérez efficacement votre plateforme de formations et vos
                    utilisateurs.
                </p>
            </div>
            <div class="hidden lg:block">
                <div
                    class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center"
                >
                    <span
                        class="material-symbols-outlined text-6xl text-white text-opacity-80"
                        >settings</span
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- Stats rapide -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @include('clean.admin.partials.stats.usersStats', ['team' => $team])
        @include('clean.admin.partials.stats.formationsStats', [ 'team' =>
        $team])
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
        >
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center"
                >
                    <span
                        class="material-symbols-outlined text-purple-600 dark:text-purple-400"
                        >analytics</span
                    >
                </div>
                <div>
                    <div
                        class="text-2xl font-bold text-slate-900 dark:text-white"
                    >
                        0
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        Inscriptions ce mois
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section actions rapides -->
    @include('clean.admin.partials.configuration')
</div>
