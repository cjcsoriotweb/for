<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">school</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Gestionnaire de formations</h2>
                <p class="text-blue-100 text-sm">Activez ou désactivez les formations pour {{ $team->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Catalogue des formations</h1>
                    <p class="text-blue-100 text-lg mb-6">Gérez l'accès aux formations pour votre équipe.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">library_books</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ dd($formations->where('is_active','=',1)->count()) }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Formations activées</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-400">library_books</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $formations->count() }}</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Total des formations</div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">trending_up</span>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ round(($formations->count() / max($formations->count(), 1)) * 100) }}%</div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">Taux d'activation</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formations Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($formations as $formation)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if ($formation->is_attached)
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">Activée</span>
                            @else
                                <div class="w-3 h-3 bg-slate-400 rounded-full"></div>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Désactivée</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">
                            ID: {{ $formation->id }}
                        </div>
                    </div>

                    <!-- Formation Content -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-2">{{ $formation->title }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $formation->description }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                @if ($formation->is_attached)
                                    {{ __('Disponible pour les élèves')}}
                                @else
                                    {{ __('Non disponible')}}
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                @if ($formation->is_attached)
                                    <form action="{{ route('application.admin.formations.disable', [$team, $formation]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                           

                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-gray font-medium rounded-lg text-sm transition-colors">
                                            {{ __('Desactiver cette formation') }}
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('application.admin.formations.enable', [$team, $formation]) }}" method="POST" class="inline relative opacity-50 hover:opacity-100">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                         
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 rounded-lg text-white text-xs font-medium transition-opacity">
                                            <span class="material-symbols-outlined mr-1 text-sm">check</span> Activer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($formations->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-2xl text-slate-400">library_books</span>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Aucune formation disponible</h3>
                <p class="text-slate-600 dark:text-slate-400">Il n'y a actuellement aucune formation à gérer.</p>
            </div>
        @endif

        <!-- Navigation retour -->
        <div class="text-center">
            <a href="{{ route('application.admin.formations.index', $team) }}"
                class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                Retour à la gestion des formations
            </a>
        </div>
    </div>
</x-application-layout>
