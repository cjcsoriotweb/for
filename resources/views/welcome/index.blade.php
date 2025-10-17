<x-simple-menu-layout>
    <x-slot name="title">{{__('Bienvenue sur votre application de formation')}}</x-slot>
    <x-slot name="description">{{__('Vous êtes déjà inscrit ?')}}</x-slot>
    <!-- Fond stylé avec éléments décoratifs modernes -->
    <a href="{{ route('vous.index') }}"
        class="group w-full flex items-center p-5 border border-gray-200/60 dark:border-gray-600/60 rounded-2xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10 hover:border-blue-300 dark:hover:border-blue-600 hover:-translate-y-0.5">
        <div class="flex-shrink-0 mr-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 group-hover:scale-110 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="text-left flex-1">
            <div class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                {{ __("J'ai un compte.")}}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            {{ __('Connectez vous à votre compte') }}    
            </div>
        </div>
        <div class="flex-shrink-0 ml-4">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

    <a href="{{ route('register') }}"
        class="group w-full flex items-center p-5 border border-violet-200/60 dark:border-violet-600/60 rounded-2xl hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 dark:hover:from-violet-900/20 dark:hover:to-purple-900/20 transition-all duration-300 hover:shadow-lg hover:shadow-violet-500/10 hover:border-violet-300 dark:hover:border-violet-600 hover:-translate-y-0.5">
        <div class="flex-shrink-0 mr-4">
            <div
                class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg shadow-violet-500/25 group-hover:shadow-violet-500/40 group-hover:scale-110 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="text-left flex-1">
            <div class="text-base font-semibold text-violet-900 dark:text-white mb-1">
                {{ __("Je n'ai pas de compte.")}}
            </div>
            <div class="text-sm text-violet-500 dark:text-violet-400 leading-relaxed">
            {{ __("C'est la première fois que je viens ici.") }}    
            </div>
        </div>
        <div class="flex-shrink-0 ml-4">
            <svg class="w-5 h-5 text-violet-400 dark:text-violet-500 group-hover:text-violet-500 dark:group-hover:text-violet-400 transition-colors duration-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

</x-simple-menu-layout>