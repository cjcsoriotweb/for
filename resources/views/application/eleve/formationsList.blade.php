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
                </div>
            </div>
        </div>

        <!-- Liste des formations -->
            @foreach($formationsByTeam as $formation)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">

            <div class="p-8">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400">library_books</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Formation de test</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">Une description de la formation de test.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 mt-4">
                    <div>
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Voir cette formation
                        </a>
                    </div>
                </div>
            </div>
        </div>

            @endforeach

        <!-- Aide et support -->
        <div style="display:none;" class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-8 border border-blue-200 dark:border-blue-800">
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


</x-application-layout>
