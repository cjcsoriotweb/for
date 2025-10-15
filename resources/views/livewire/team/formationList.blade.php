<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12">
        <div class="flex items-center space-x-4 mb-4">
            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl text-slate-600 dark:text-slate-400">school</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Formations</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">Découvrez et suivez vos formations professionnelles</p>
            </div>
        </div>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        @forelse($formations as $formation)

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">
            <!-- Header with icon -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-400">business_center</span>
                    </div>
                    @if( $formation->formation_user )
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full flex items-center shrink-0">
                        <span class="material-symbols-outlined text-sm mr-1">timelapse</span>
                        En cours
                    </span>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white leading-tight">{{ $formation->title }}</h2>
                <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm leading-relaxed">{{ $formation->description }}</p>
            </div>

            <!-- Content -->
            <div class="p-6 flex-1 flex flex-col">
                <!-- Progress bar for enrolled formations -->
                @if($formation->formation_user)
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Progression</span>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $formation->formation_user->progress_percent }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-slate-700 dark:bg-slate-300 h-2 rounded-full transition-all duration-300" style="width: {{ $formation->formation_user->progress_percent }}%"></div>
                    </div>
                </div>
                @endif

                <!-- Admin controls -->
                @if($display === 'admin')
                <div class="mt-auto">
                    @if($formation->pivot_active)
                    <form method="POST" action="{{ route('application.admin.formations.disable', [$team,$formation]) }}">
                        @csrf
                        <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                        <button type="submit" class="w-full focus:outline-none text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors duration-200">
                            <span class="material-symbols-outlined text-sm mr-2">block</span>
                            Désactiver
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('application.admin.formations.enable', [$team,$formation]) }}">
                        @csrf
                        <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                        <button type="submit" class="w-full focus:outline-none text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors duration-200">
                            <span class="material-symbols-outlined text-sm mr-2">check_circle</span>
                            Activer
                        </button>
                    </form>
                    @endif
                </div>
                @endif

                <!-- Student actions -->
                @if($display === 'eleve')
                <div class="mt-auto">
                    @if( $formation->formation_user )
                    <a href="{{ route('application.eleve.formations.continue', [$team, $formation]) }}"
                        class="w-full inline-flex items-center justify-center py-2.5 px-4 border border-slate-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-lg text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 transition-colors duration-200">
                        <span class="material-symbols-outlined text-sm mr-2">play_arrow</span>
                        Continuer
                    </a>
                    @else
                    <a href="{{ route('application.eleve.formations.preview', [$team, $formation]) }}"
                        class="w-full inline-flex items-center justify-center py-2.5 px-4 border border-slate-700 dark:border-slate-300 shadow-sm text-sm font-medium rounded-lg text-white bg-slate-700 dark:bg-slate-300 hover:bg-slate-800 dark:hover:bg-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-500 transition-colors duration-200">
                        <span class="material-symbols-outlined text-sm mr-2">rocket_launch</span>
                        Commencer
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
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
