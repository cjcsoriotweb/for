<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">library_books</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Catalogue des formations</h2>
                <p class="text-purple-100 text-sm">Découvrez toutes les formations disponibles</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Intro section -->
        <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Explorez notre catalogue</h1>
                    <p class="text-purple-100 text-lg mb-6">Accédez à des formations de qualité pour développer vos compétences.</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">filter_list</span>
                            Toutes les catégories
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">local_fire_department</span>
                            Tendances
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                            <span class="material-symbols-outlined text-sm mr-1">star</span>
                            Populaires
                        </span>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white/80">explore</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400">search</span>
                        </div>
                        <input type="text" placeholder="Rechercher une formation..."
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <select class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Toutes les catégories</option>
                        <option>Technique</option>
                        <option>Management</option>
                        <option>Communication</option>
                    </select>
                    <select class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Trier par</option>
                        <option>Plus récentes</option>
                        <option>Plus populaires</option>
                        <option>Note décroissante</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Liste des formations -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-8">
                @livewire('FormationList', ['team' => $team, 'display'=>'eleve'])
            </div>
        </div>

        <!-- Aide et support -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-8 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">help_center</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-blue-800 dark:text-blue-200 mb-2">Besoin d'aide ?</h3>
                    <p class="text-blue-700 dark:text-blue-300">
                        Vous ne trouvez pas la formation idéale ? Contactez notre équipe pédagogique pour des recommandations personnalisées.
                    </p>
                </div>
                <div class="hidden md:block">
                    <button class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                        <span class="material-symbols-outlined mr-2">contact_support</span>
                        Nous contacter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-block-div>
        <x-block-navigation :navigation="[
        ]" card="bg-white dark:bg-slate-800" :team="$team" back="{{ route('application.eleve.index',$team) }}" />
    </x-block-div>

</x-application-layout>
