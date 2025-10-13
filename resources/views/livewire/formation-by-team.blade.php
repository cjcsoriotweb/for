<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-10">Mes Formations</h1>
    <div class="space-y-8">
        @forelse($formations as $formation)
        <div
            class="bg-white dark:bg-gray-800/50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col lg:flex-row transform hover:-translate-y-1">
            <div class="w-full lg:w-1/3 h-56 lg:h-auto bg-cover bg-center"
                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBZOAyTLsUVYOH664CceZ4BLWDoijkS6Wd4ioO8jp938ceKj6ALdUQfMjQL-IaFltRvB-0xIgyCOvFDR9IH3QagZic7l-ravkxs-w1dEBCZe5fW9CB7FcbeOvtrACQP9n1_mWRIbe2Efd4JYTcBAzvCYSIPw8LSlStYl80zobB59rPJRbkalO3P_umt2zpv-pLqAZb1zcmR4VN8U4S7itruqpq39sj5JC1sEZjnz6Rkih5iW2NAl381X1OXeE8l83BA0EYo7iryqxSD');">
            </div>
            <div class="p-6 lg:p-8 flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $formation->formation->title }}
                        </h2>
                        <span
                            class="bg-blue-100 text-primary text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                            <span class="material-symbols-outlined text-sm mr-1">timelapse</span>
                            En cours
                        </span>
                    </div>
                    
                            <p class="text-gray-600 dark:text-gray-400 mt-2 mb-4">{{ $formation->formation->description }}</p>
                </div>
                <div>
                    <div class="mb-2">
                        @if($formation->formation_user)
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progression</span>
                            <span class="text-sm font-medium text-primary">{{ $formation->formation_user->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $formation->formation_user->progress }}%"></div>
                        </div>
                        @endif

                    </div>
                    <div class="mt-6 flex justify-end">
                        <a class="text-primary hover:text-primary/80 font-semibold flex items-center transition-colors">
                            Continuer la formation
                            <span class="material-symbols-outlined ml-1">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-600 dark:text-gray-400">Aucune formation en cours pour le moment.</p>
        @endforelse
    </div>
    <x-big-button>Trouver d'autre formation</x-big-button>
    <x-debug>livewire:formation-by-team | {{ $team->name }}</x-debug>
</div>