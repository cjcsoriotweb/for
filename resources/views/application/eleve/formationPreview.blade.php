<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title  }}
        </h2>
        <x-slot name="subtitle">Aperçu avant inscription</x-slot>
        <x-slot name="headerIcon">visibility</x-slot>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Présentation de la formation -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-8">
                <div class="flex items-start space-x-6">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-3xl text-slate-600 dark:text-slate-400">school</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">{{ $formation->title }}</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">{{ $formation->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations sur le coût et fonds -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="border-b border-slate-200 dark:border-slate-700 px-8 py-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">credit_card</span>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Conditions d'accès</h2>
                </div>
            </div>

            <div class="p-8">
                <!-- Coût et fonds disponibles -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">shopping_cart</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Coût de la formation</div>
                                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $formation->money_amount }}€</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">account_balance_wallet</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Fonds disponibles</div>
                                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $team->money }}€</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barre de progression visuelle des fonds -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Couverture des fonds</span>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ min(100, round(($team->money / max(1, $formation->money_amount)) * 100)) }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3">
                        <div class="bg-slate-700 dark:bg-slate-300 h-3 rounded-full transition-all duration-300"
                            style="width: {{ min(100, round(($team->money / max(1, $formation->money_amount)) * 100)) }}%"></div>
                    </div>
                </div>

                <!-- Message d'insuffisance de fonds -->
                @if($formation->money_amount > $team->money)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-red-600 dark:text-red-400">warning</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">Fonds insuffisants</h4>
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    Il manque <strong>{{ $formation->money_amount - $team->money }}€</strong> pour accéder à cette formation.
                                    Contactez votre administrateur d'équipe pour augmenter les fonds disponibles.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section des actions -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    @if($formation->money_amount > $team->money)
                        <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-red-600 dark:text-red-400">block</span>
                        </div>
                    @else
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">check_circle</span>
                        </div>
                    @endif
                </div>

                <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Prêt à commencer ?</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
                    @if($formation->money_amount > $team->money)
                        Vous ne pouvez pas encore accéder à cette formation. Les fonds de l'équipe doivent être augmentés.
                    @else
                        En cliquant sur le bouton ci-dessous, vous vous inscrivez à cette formation et les fonds seront débités de votre équipe.
                    @endif
                </p>

                <form method="POST" action="{{ route('application.eleve.formations.enable', [$team, $formation]) }}" class="inline-block">
                    @csrf
                    <input type="hidden" name="formation" value="{{ $formation->id }}">

                    <button type="submit"
                        @if($formation->money_amount > $team->money) disabled class="opacity-50 cursor-not-allowed" @endif
                        class="inline-flex items-center px-8 py-3 bg-slate-900 hover:bg-slate-800 disabled:hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition-colors duration-200 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined mr-3">{{ $formation->money_amount > $team->money ? 'lock' : 'rocket_launch' }}</span>
                        {{ $formation->money_amount > $team->money ? 'Fonds insuffisants' : 'Commencer cette formation' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-application-layout>
