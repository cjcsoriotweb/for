<div class="relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-10">Formation disponible pour {{ Auth::user()->currentTeam->name ?? '' }}</h1>
        <div class="space-y-8">

            @foreach($formations as $formation)

            <div
                class="bg-white dark:bg-gray-800/50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col lg:flex-row transform hover:-translate-y-1">
                <div class="w-full lg:w-1/3 h-56 lg:h-auto bg-cover bg-center"
                    style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBZOAyTLsUVYOH664CceZ4BLWDoijkS6Wd4ioO8jp938ceKj6ALdUQfMjQL-IaFltRvB-0xIgyCOvFDR9IH3QagZic7l-ravkxs-w1dEBCZe5fW9CB7FcbeOvtrACQP9n1_mWRIbe2Efd4JYTcBAzvCYSIPw8LSlStYl80zobB59rPJRbkalO3P_umt2zpv-pLqAZb1zcmR4VN8U4S7itruqpq39sj5JC1sEZjnz6Rkih5iW2NAl381X1OXeE8l83BA0EYo7iryqxSD');">
                </div>
                <div class="p-6 lg:p-8 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $formation->title }}
                            </h2>
                            <span
                                class="bg-gradient-to-r from-yellow-200 to-orange-200 text-gray-800 text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-sm">attach_money</span>
                                <span>{{ $formation->money_amount }}</span>
                                <span class="text-gray-600 italic">par utilisateur</span>
                                <span class="material-symbols-outlined text-sm">person</span>
                            </span>
                              <span
                                class="text-dark text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">block</span>
                                Désactivé
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mt-2 mb-4">Apprenez les concepts et techniques
                            avancés de Python pour construire des applications robustes.</p>
                    </div>
                    <div>
                        <div class="mt-6 flex justify-end">
                            <a class="text-dark hover:text-primary/80 font-semibold flex items-center transition-colors"
                                href="#">
                                Activer cette formation
                                <span class="material-symbols-outlined ml-1">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>

</div>