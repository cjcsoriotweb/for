<nav class="bg-transparent backdrop-blur-md border-b border-gray-200/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center space-x-8">
                <!-- Logo -->


                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center space-x-1">


                    <x-nav-link :href="route('team.admin.index', ['team' => Auth::user()->current_team_id])"
                        :active="request()->routeIs('team.admin.index')" class="group">
                        <span
                            class="flex items-center px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-colors duration-200 hover:bg-gray-50/50 rounded-lg">
                            <svg class="w-5 h-5 mr-2 text-gray-500 group-hover:text-green-500" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Paramètre
                        </span>
                    </x-nav-link>

                                        <x-nav-link :href="route('team.admin.members.index', ['team' => $team->id])"
                        :active="request()->routeIs('team.admin.members.index')" class="group">
                        <span
                            class="flex items-center px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-colors duration-200 hover:bg-gray-50/50 rounded-lg">
                            <svg class="w-5 h-5 mr-2 text-gray-500 group-hover:text-green-500" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('Utilisateurs') }}
                        </span>
                    </x-nav-link>

                    

                                        <x-nav-link :href="route('team.admin.formations.index', ['team' => Auth::user()->current_team_id])"
                        :active="request()->routeIs('team.admin.formations.index')" class="group">
                        <span
                            class="flex items-center px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-colors duration-200 hover:bg-gray-50/50 rounded-lg">
                            <svg class="w-5 h-5 mr-2 text-gray-500 group-hover:text-green-500" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Formations
                        </span>
                    </x-nav-link>

                    <!-- Money Counter -->
                    <div
                        class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-200 font-semibold">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        {{ $team->money }}€
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button @click="open = ! open" type="button"
                    class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path :class="{'block': open, 'hidden': !open}" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': open, 'block': !open}" stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>


    </div>
</nav>