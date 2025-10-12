    <!-- Welcome Screen -->
    <div id="welcome-screen" class="fixed inset-0 bg-gray-900 z-50 flex flex-col justify-center items-center">
        <div class="welcome-screen">
            <!-- User avatar -->
            <div class="mb-8">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Photo de profil" class="w-32 h-32 rounded-full border-4 border-red-500 shadow-2xl animate__animated animate__zoomIn">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-red-500 to-purple-600 flex items-center justify-center shadow-2xl animate__animated animate__zoomIn">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Welcome message -->
            <h1 class="text-6xl font-bold text-white mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                Bienvenue
            </h1>
            <h2 class="text-3xl font-semibold text-gray-300 mb-2 animate__animated animate__fadeInUp animate__delay-1s">
                {{ Auth::user()->name }}
            </h2>
            <div class="w-32 h-1 bg-red-600 mx-auto rounded-full animate__animated animate__zoomIn animate__delay-2s"></div>
        </div>
    </div>

    <!-- Main Screen -->
    <div id="main-screen" class="fixed inset-0 bg-gray-900 z-50 main-screen">
        <!-- Background overlay with subtle animation -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black">
            <div class="absolute inset-0 opacity-10">
                <div class="animate-pulse" style="background: radial-gradient(circle at 25% 25%, rgba(239, 68, 68, 0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(34, 197, 94, 0.1) 0%, transparent 50%); height: 100%;"></div>
            </div>
        </div>

        <div class="relative z-10 min-h-full flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
            <!-- Animated title -->
            <div class="text-center mb-8 animate__animated animate__fadeInDown">
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-wide animate__animated animate__bounceIn">
                    {{ __('Sélectionnez votre application') }}
                </h1>
                <div class="w-24 h-1 bg-red-600 mx-auto rounded-full animate__animated animate__zoomIn animate__delay-1s"></div>
            </div>

            @if (Auth::user()->allTeams()->count() > 0)
                <!-- Horizontal scrollable row layout -->
                <div class="flex space-x-8 overflow-x-auto pb-4 px-4 scrollbar-hide ">
                    @foreach (Auth::user()->allTeams() as $team)
                        <form method="POST" action="{{ route('teams.switch', $team) }}" class="bg-white rounded-2xl shadow-lg group animate__animated animate__fadeInUp flex-none" style="animation-delay: {{ $loop->index * 0.15 }}s;">
                            @csrf
                            <button type="submit" class="text-left focus:outline-none focus:ring-4 focus:ring-red-500 focus:ring-offset-4 focus:ring-offset-gray-900 rounded-xl overflow-hidden transform transition-all duration-500 hover:scale-105 hover:rotate-1 hover:z-20 hover:shadow-2xl hover:shadow-red-500/30 animate__animated animate__pulse animate__infinite animate__slower">
                                <div class="relative  rounded-xl overflow-hidden shadow-2xl transform transition-all duration-500 w-80 h-80">
                                    <!-- Team Image or Animated Placeholder -->
                                    <div class="w-full h-full relative overflow-hidden">
                                        @if($team->profile_photo_path)
                                            <!-- Team Image -->
                                            <img src="{{ $team->profile_photo_url }}" style="object-fit: scale-down;" alt="{{ $team->name }}" class="w-full h-full object-cover animate__animated animate__fadeIn transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            <!-- Animated Placeholder -->
                                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center relative">

                                                <!-- Animated icon -->
                                                <div class="relative z-10 animate__animated animate__zoomIn animate__delay-1s">
                                                    <svg class="w-20 h-20 text-gray-300 opacity-80 group-hover:text-white transition-colors duration-300 animate__animated animate__bounce animate__delay-2s" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>

                                                <!-- Floating particles -->
                                                <div class="absolute inset-0 pointer-events-none">
                                                    @for ($i = 0; $i < 5; $i++)
                                                        <div class="absolute w-2 h-2 bg-white/20 rounded-full animate__animated animate__fadeIn animate__infinite animate__slower" style="top: {{ rand(10, 90) }}%; left: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.5 }}s; animation-duration: {{ rand(3, 6) }}s;"></div>
                                                    @endfor
                                                </div>
                                            </div>
                                        @endif
                                    </div>

  

                                    <!-- Static title at bottom -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent animate__animated animate__fadeIn animate__delay-1s">
                                        <h3 class="text-white font-semibold text-lg truncate">{{ $team->name }}</h3>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach
                </div>
            @else
                <!-- Animated empty state -->
                <div class="text-center py-12 animate__animated animate__fadeIn animate__delay-1s">
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-12 max-w-lg mx-auto border border-gray-700 animate__animated animate__zoomIn animate__delay-1s">
                        <div class="animate__animated animate__bounce animate__infinite animate__slower">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6 animate__animated animate__pulse animate__delay-2s" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-white text-2xl font-bold mb-4 animate__animated animate__fadeInUp animate__delay-1s">Aucune application trouvée</h3>
                        <p class="text-gray-400 text-lg animate__animated animate__fadeInUp animate__delay-1s">Vous n'avez accès à aucune application pour le moment.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show welcome screen for 3 seconds, then transition to main screen
            setTimeout(() => {
                const welcomeScreen = document.getElementById('welcome-screen');
                const mainScreen = document.getElementById('main-screen');

                welcomeScreen.style.animation = 'slideOutLeft 0.6s ease-in forwards';
                setTimeout(() => {
                    welcomeScreen.style.display = 'none';
                    mainScreen.classList.add('show');
                }, 600);
            }, 3000);
        });
    </script>
