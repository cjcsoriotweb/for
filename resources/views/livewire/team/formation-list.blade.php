<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 slide-in-up">
        <div class="flex items-center space-x-4 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg floating-element">
                <span class="material-symbols-outlined text-2xl text-white">school</span>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 dark:from-white dark:via-slate-100 dark:to-slate-300 bg-clip-text text-transparent">Formations</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-2 text-lg">Découvrez et suivez vos formations professionnelles</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-600 bg-opacity-10 to-purple-600 bg-opacity-10 dark:from-blue-900 dark:bg-opacity-20 dark:to-purple-900 dark:bg-opacity-20 rounded-2xl p-6 mt-6 border border-blue-200 border-opacity-50 dark:border-blue-800 dark:border-opacity-50">
            <p class="text-center text-slate-700 dark:text-slate-300">Votre espace d'apprentissage personnalisé avec des parcours pédagogiques adaptés à vos besoins.</p>
        </div>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($formations as $formation)
        <x-formation-card
            :formation="$formation"
            :isEnrolled="$formation->formation_user ? true : false"
            :formationUser="$formation->formation_user"
            :isAdminMode="$display === 'admin'"
            :team="$team"
        />
        @empty
        <div class="col-span-full">
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-slate-400">school</span>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Aucune formation disponible</h3>
                <p class="text-slate-600 dark:text-slate-400">Les formations seront bientôt disponibles pour votre équipe.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
