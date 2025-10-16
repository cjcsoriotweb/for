<x-guest-layout>
    <!-- Modal de bienvenue -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-8">
                <!-- En-tête du modal -->
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary/10 mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{__('Bienvenue sur votre application de formation')}}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Comment souhaitez-vous utiliser notre plateforme
                        ?</p>
                </div>

                <!-- Options -->
                <div class="space-y-3 mb-8">


                    <a href="{{ route('vous.index') }}"
                        class="w-full flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Continuer</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Avec votre compte
                                <br>{{ Auth::user()->email }}
                            </div>
                        </div>
                    </a>

                    @if(Auth::user()->superadmin)
                    <a href="{{ route('superadmin.home') }}"
                        class="w-full flex items-center p-4 border border-red-200 dark:border-red-600 rounded-xl hover:bg-red-50 dark:hover:bg-gray-700 transition-colors group">
                        <div class="flex-shrink-0 mr-4">
                            <div
                                class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">Superadmin</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Oui, c'est dangereux.
                            </div>
                        </div>
                    </a>
                    @endif


                </div>

                <!-- Bouton pour fermer sans choisir -->
                <div class="text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                            {{ __('Déconnexion') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</x-guest-layout>
