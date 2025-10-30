<x-guest-layout>

    <div class="fixed inset-0 z-40">
        <!-- Gradient de fond principal amélioré -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800">
        </div>

        <!-- Motifs géométriques en arrière-plan -->
        <div class="absolute inset-0 opacity-30 dark:opacity-20">
            <!-- Grille de points subtile -->
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.15) 1px, transparent 0); background-size: 40px 40px;">
            </div>
        </div>

        <!-- Formes organiques modernes - Responsive -->
        <div
            class="absolute top-16 left-16 w-72 h-72 bg-gradient-to-br from-blue-400/20 via-indigo-500/15 to-purple-600/20 rounded-full blur-3xl animate-pulse sm:w-72 sm:h-72 w-48 h-48 sm:left-16 left-8 sm:top-16 top-12">
        </div>
        <div
            class="absolute top-32 right-20 w-56 h-56 bg-gradient-to-br from-emerald-400/20 via-teal-500/15 to-cyan-600/20 rounded-full blur-2xl animate-pulse delay-1000 sm:w-56 sm:h-56 w-36 h-36 sm:right-20 right-8 sm:top-32 top-24">
        </div>
        <div
            class="absolute bottom-20 left-1/3 w-48 h-48 bg-gradient-to-br from-violet-400/20 via-purple-500/15 to-pink-600/20 rounded-full blur-2xl animate-pulse delay-500 sm:w-48 sm:h-48 w-32 h-32 sm:bottom-20 bottom-16 sm:left-1/3 left-1/4">
        </div>

        <!-- Formes géométriques flottantes - Responsive -->
        <div
            class="absolute top-24 left-1/4 w-4 h-4 bg-blue-500/30 rounded-full animate-bounce delay-300 sm:w-4 sm:h-4 w-3 h-3 sm:top-24 sm:left-1/4 top-16 left-1/3">
        </div>
        <div
            class="absolute top-1/3 right-1/4 w-3 h-3 bg-indigo-500/40 rounded-full animate-bounce delay-700 sm:w-3 sm:h-3 w-2 h-2 sm:top-1/3 sm:right-1/4 top-1/4 right-1/3">
        </div>
        <div
            class="absolute bottom-1/3 left-1/2 w-2 h-2 bg-purple-500/50 rounded-full animate-bounce delay-1000 sm:w-2 sm:h-2 w-1.5 h-1.5 sm:bottom-1/3 sm:left-1/2 bottom-1/4 left-2/3">
        </div>

        <!-- Lignes de connexion subtiles -->
        <svg class="absolute inset-0 w-full h-full opacity-20 dark:opacity-10" viewBox="0 0 400 400">
            <path d="M50,50 Q200,100 350,80" stroke="rgb(59, 130, 246)" stroke-width="1" fill="none"
                class="animate-pulse" />
            <path d="M30,150 Q180,120 320,140" stroke="rgb(139, 92, 246)" stroke-width="1" fill="none"
                class="animate-pulse delay-500" />
            <path d="M60,250 Q220,220 340,240" stroke="rgb(16, 185, 129)" stroke-width="1" fill="none"
                class="animate-pulse delay-1000" />
        </svg>

        <!-- Particules flottantes - Responsive -->
        <div
            class="absolute top-1/4 left-1/3 w-1 h-1 bg-blue-400/60 rounded-full animate-ping sm:w-1 sm:h-1 w-0.5 h-0.5 sm:top-1/4 sm:left-1/3 top-1/5 left-1/4">
        </div>
        <div
            class="absolute top-2/3 right-1/4 w-1 h-1 bg-emerald-400/60 rounded-full animate-ping delay-300 sm:w-1 sm:h-1 w-0.5 h-0.5 sm:top-2/3 sm:right-1/4 top-3/4 right-1/3">
        </div>
        <div
            class="absolute bottom-1/4 left-1/4 w-1 h-1 bg-violet-400/60 rounded-full animate-ping delay-700 sm:w-1 sm:h-1 w-0.5 h-0.5 sm:bottom-1/4 sm:left-1/4 bottom-1/3 left-1/5">
        </div>
        <div
            class="absolute top-1/2 right-1/3 w-0.5 h-0.5 bg-indigo-400/80 rounded-full animate-ping delay-1000 sm:w-0.5 sm:h-0.5 w-0.5 h-0.5 sm:top-1/2 sm:right-1/3 top-2/3 right-1/5">
        </div>
    </div>

    <!-- Overlay pour la lisibilité -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm animate-in fade-in duration-300">
        <div
            class="bg-white/95 dark:bg-gray-800/95 rounded-3xl shadow-2xl max-w-md w-full mx-4 transform transition-all animate-in zoom-in-95 duration-300 border border-white/30 dark:border-gray-700/30 backdrop-blur-xl">
            <div class="p-8">
                <!-- En-tête du modal -->
                <div class="text-center mb-8">
                    <div
                        class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-blue-500/10 to-purple-500/10 mb-6 ring-1 ring-gray-200/50 dark:ring-gray-700/50">
                        <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h2
                        class="text-3xl font-bold text-gray-900 dark:text-white mb-3 bg-none dark:from-none dark:to-none">
                        {{ $title}}</h2>
                    <p class="text-gray-900 dark:text-gray-200 text-base leading-relaxed">{{ $description}}</p>
                </div>

                <!-- Options -->
                <div class="space-y-4 mb-8">
                    {{ $slot }}
                </div>

                <!-- Bouton pour fermer sans choisir -->
                @if(auth())
                <div class="text-center pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
                    <x-forms.auth.logout class="inline">
                        <button type="submit"
                            class="group inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 hover:scale-105">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-200"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Déconnexion') }}
                        </button>
                    </x-forms.auth.logout>
                </div>
                @endif
            </div>
        </div>
    </div>

</x-guest-layout>