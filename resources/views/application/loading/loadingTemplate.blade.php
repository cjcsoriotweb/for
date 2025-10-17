<x-application-layout :team="$team">

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Accueil') }} <b>{{ $team->name }}</b>
        </h2>
    </x-slot>

    <div class="h-screen w-screen fixed inset-0 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-md w-full mx-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 text-center transform transition-all duration-300 hover:scale-105">
                <!-- Animation de chargement principale -->
                <div class="relative mb-8">
                    <!-- Cercle de chargement animé -->
                    <div class="w-24 h-24 mx-auto mb-6 relative">
                        <div class="absolute inset-0 border-4 border-blue-200 dark:border-blue-700 rounded-full"></div>
                        <div class="absolute inset-0 border-4 border-transparent border-t-blue-600 dark:border-t-blue-400 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 border-4 border-transparent border-t-indigo-600 dark:border-t-indigo-400 rounded-full animate-spin animation-delay-300">
                        
                        </div>
                            <img style="object-fit: scale-down;transform: scale(0.5);"  src="{{ asset('storage/'.$team->profile_photo_path) }}" alt="" class="w-full h-full object-cover rounded-full">
                        
                    </div>

                    <!-- Icône élève stylisée -->
                    <div class="text-6xl mb-4 animate-bounce">
                        {{ $icon }}
                    </div>
                </div>

                <!-- Messages -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Connexion en cours...
                    </h3>

                    <p class="text-lg text-gray-600 dark:text-gray-300">
                        Vous êtes identifié comme <span class="font-semibold text-blue-600 dark:text-blue-400">{{ Auth::user()->teamRole($team)->name }}</span>
                    </p>

                    <div class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse animation-delay-150"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse animation-delay-300"></div>
                        </div>
                        <span class="text-sm">Redirection vers votre espace {{ Auth::user()->teamRole($team)->name }}</span>
                    </div>
                </div>

                <!-- Barre de progression -->
                <div class="mt-8 mb-6">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full animate-pulse"
                             style="width: 75%; animation: loading-progress 2s ease-in-out infinite alternate;"></div>
                    </div>
                </div>

                <!-- Lien de secours -->
                <div id="fallback-link" class="hidden">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        La redirection ne fonctionne pas ?
                    </p>
                    <a href="$redirectUrl"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        Cliquer ici pour accéder à votre espace élève
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes loading-progress {
            0% { width: 25%; }
            100% { width: 90%; }
        }

        .animation-delay-150 { animation-delay: 150ms; }
        .animation-delay-300 { animation-delay: 300ms; }
    </style>


    <script>
        // Redirection automatique après 3 secondes
        setTimeout(function() {
            window.location.href = '{{ $redirectUrl }}';
        }, 3000);

        // Afficher le lien de secours après 5 secondes si la redirection ne marche pas
        setTimeout(function() {
            document.getElementById('fallback-link').classList.remove('hidden');
        }, 5000);
    </script>

</x-application-layout>
