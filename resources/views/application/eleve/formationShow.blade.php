<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title }}
        </h2>
        <x-slot name="subtitle">Détails de la formation professionnelle</x-slot>
        <x-slot name="headerIcon">school</x-slot>
        <x-slot name="headerActions">
            <a href="{{ route('application.eleve.formations.preview', [$team, $formation]) }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-lg text-sm font-medium transition-colors duration-200">
                <span class="material-symbols-outlined mr-2 text-sm">rocket_launch</span>
                Commencer la formation
            </a>
        </x-slot>
    </x-slot>

    <div class="py-8">
        <!-- Card principale d'information -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400">school</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ $formation->title }}</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed mb-6">{{ $formation->description }}</p>

                        <!-- Métadonnées de la formation -->
                        <div class="grid md:grid-cols-3 gap-6 mb-6">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg mb-2">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">{{ $formation->money_amount > 0 ? 'credit_card' : 'free_cancellation' }}</span>
                                </div>
                                <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $formation->money_amount > 0 ? $formation->money_amount . '€' : 'Gratuit' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $formation->money_amount > 0 ? 'Coût' : 'Accès libre' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg mb-2">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">menu_book</span>
                                </div>
                                <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $formation->chapters->count() }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Chapitre{{ $formation->chapters->count() > 1 ? 's' : '' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg mb-2">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">schedule</span>
                                </div>
                                <div class="text-sm font-medium text-slate-900 dark:text-white">Auto-rythmé</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Durée estimée</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des chapitres -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="border-b border-slate-200 dark:border-slate-700 px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">list_alt</span>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Programme de formation</h2>
                </div>
            </div>

            <div class="p-8">
                @forelse($formation->chapters as $chapter)
                    <div class="flex items-start space-x-4 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg mb-4 border border-slate-200 dark:border-slate-600">
                        <div class="w-8 h-8 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ $loop->iteration }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 dark:text-white mb-1">{{ $chapter->title }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">{{ $chapter->description }}</p>
                        </div>
                        <div class="flex-shrink-0 text-slate-400 dark:text-slate-500">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-2xl text-slate-400">library_books</span>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Aucun chapitre disponible</h3>
                        <p class="text-slate-600 dark:text-slate-400">Le contenu de cette formation sera bientôt ajouté.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Bouton d'action principal -->
        <div class="mt-8 text-center">
            <a href="{{ route('application.eleve.formations.preview', [$team, $formation]) }}"
                class="inline-flex items-center px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200">
                <span class="material-symbols-outlined mr-3">play_arrow</span>
                Commencer cette formation
            </a>
        </div>
    </div>
</x-application-layout>
