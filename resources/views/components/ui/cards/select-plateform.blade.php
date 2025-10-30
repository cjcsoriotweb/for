    @props([
    'title' => __('Sélectionnez votre application'),
    'items' => Auth::user()->allTeams(),
    'route' => 'teams.switch'
])

<!-- Main Screen -->
    <div id="main-screen" class="fixed inset-0 bg-gray-900 z-50 main-screen show">
        <!-- Background overlay with subtle animation -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black">
            <div class="absolute inset-0 opacity-10">
                <div class="animate-pulse" style="background: radial-gradient(circle at 25% 25%, rgba(239, 68, 68, 0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(34, 197, 94, 0.1) 0%, transparent 50%); height: 100%;"></div>
            </div>
        </div>

        <div class="relative z-10 min-h-full flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
            <!-- Animated title -->
            <div class="text-center mb-8 animate__animated animate__fadeInDown">
                <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white mb-4 tracking-wide animate__animated animate__bounceIn">
                    {{ $title }}
                </h1>
                <div class="w-24 h-1 bg-red-600 mx-auto rounded-full animate__animated animate__zoomIn animate__delay-1s"></div>
            </div>

            @if ($items->count() > 0)
                <!-- Horizontal scrollable row layout -->
                <div class="flex space-x-4 sm:space-x-6 md:space-x-8 overflow-x-auto pb-4 px-4 scrollbar-hide ">
                    @foreach ($items as $item)
                        <x-forms.ui.select-plateform-card
                            :item="$item"
                            :route="$route"
                            :delay="$loop->index * 0.15"
                        />
                    @endforeach
                </div>
            @else
                <!-- Animated empty state -->
                <div class="text-center py-12 animate__animated animate__fadeIn animate__delay-1s">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-12 max-w-lg mx-auto border border-gray-700 animate__animated animate__zoomIn animate__delay-1s">
                        <div class="animate__animated animate__bounce animate__infinite animate__slower">
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 text-gray-400 mx-auto mb-6 animate__animated animate__pulse animate__delay-2s" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-white text-xl sm:text-2xl font-bold mb-4 animate__animated animate__fadeInUp animate__delay-1s">Aucune application trouvée</h3>
                        <p class="text-gray-400 text-base sm:text-lg animate__animated animate__fadeInUp animate__delay-1s">Vous n'avez accès à aucune application pour le moment.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Custom CSS gradients and animations -->
    <style>
        @keyframes move {
            from { background-position: 0 0; }
            to { background-position: 100px 100px; }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideOutLeft {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(-100%); opacity: 0; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate__delay-1s { animation-delay: 1s; }
        .animate__delay-2s { animation-delay: 2s; }
        .animate__slower { animation-duration: 3s; }
        .welcome-screen { animation: fadeInUp 1s ease-out; }
        .main-screen { opacity: 0; pointer-events: none; }
        .main-screen.show { opacity: 1; pointer-events: auto; animation: slideInRight 0.8s ease-out; }

        /* Square aspect ratio */
        .aspect-square { aspect-ratio: 1 / 1; }

        /* Custom hover glowing effect */
        .group:hover .group {
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
        }

        /* Hide scrollbar for horizontal scroll */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
