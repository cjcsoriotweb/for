@props(['formations' => formations()])
<div class="relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-10">Mes Formations</h1>
        <div class="space-y-8">

            <div
                class="bg-white dark:bg-gray-800/50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col lg:flex-row transform hover:-translate-y-1">
                <div class="w-full lg:w-1/3 h-56 lg:h-auto bg-cover bg-center"
                    style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBZOAyTLsUVYOH664CceZ4BLWDoijkS6Wd4ioO8jp938ceKj6ALdUQfMjQL-IaFltRvB-0xIgyCOvFDR9IH3QagZic7l-ravkxs-w1dEBCZe5fW9CB7FcbeOvtrACQP9n1_mWRIbe2Efd4JYTcBAzvCYSIPw8LSlStYl80zobB59rPJRbkalO3P_umt2zpv-pLqAZb1zcmR4VN8U4S7itruqpq39sj5JC1sEZjnz6Rkih5iW2NAl381X1OXeE8l83BA0EYo7iryqxSD');">
                </div>
                <div class="p-6 lg:p-8 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Programmation Python Avancée
                            </h2>
                            <span
                                class="bg-blue-100 text-primary text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                                <span class="material-symbols-outlined text-sm mr-1">timelapse</span>
                                En cours
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mt-2 mb-4">Apprenez les concepts et techniques
                            avancés de Python pour construire des applications robustes.</p>
                    </div>
                    <div>
                        <div class="mb-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progression</span>
                                <span class="text-sm font-medium text-primary">65%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <a class="text-primary hover:text-primary/80 font-semibold flex items-center transition-colors"
                                href="#">
                                Continuer la formation
                                <span class="material-symbols-outlined ml-1">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <button
        class="fixed bottom-8 right-8 bg-primary text-white rounded-full p-4 shadow-xl hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-background-dark transition-transform duration-300 hover:scale-110">
        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
    </button>
</div>