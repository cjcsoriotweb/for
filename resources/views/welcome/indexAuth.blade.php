<x-simple-menu-layout>
    <x-slot name="title">{{__('De retour')}}</x-slot>
    <x-slot name="description">{{__('Que voulez-vous faire?')}}</x-slot>

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
            <div class="text-base font-semibold text-gray-900 dark:text-white mb-1">Continuer</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Avec votre
                compte<br><span class="font-medium text-blue-600 dark:text-blue-400">{{
                    Auth::user()->email }}</span></div>
        </div>
        <div class="flex-shrink-0 ml-4">
            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 dark:group-hover:text-blue-400 transition-colors duration-300"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>
    @if(Auth())
        @if(Auth::user()->superadmin)
        <a href="{{ route('superadmin.home') }}"
            class="group w-full flex items-center p-5 border border-red-200/60 dark:border-red-600/60 rounded-2xl hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 dark:hover:from-red-900/20 dark:hover:to-orange-900/20 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10 hover:border-red-300 dark:hover:border-red-600 hover:-translate-y-0.5">
            <div class="flex-shrink-0 mr-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg shadow-red-500/25 group-hover:shadow-red-500/40 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
            <div class="text-left flex-1">
                <div class="text-base font-semibold text-gray-900 dark:text-white mb-1">Superadmin</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Accès
                    administrateur<br><span class="font-medium text-red-600 dark:text-red-400">Zone à
                        risque</span></div>
            </div>
            <div class="flex-shrink-0 ml-4">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-red-500 dark:group-hover:text-red-400 transition-colors duration-300"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
        @endif
    @endif
</x-simple-menu-layout>