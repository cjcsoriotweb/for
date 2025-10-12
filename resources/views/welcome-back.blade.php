
<x-app-layout>
    @props([
    'title' => __('Sélectionnez votre application'),
    'items' => Auth::user()->allTeams(),
    'route' => 'teams.switch'
])

            @if ($items->count() > 0)
                <!-- Horizontal scrollable row layout -->
                <div class="flex flex-wrap justify-center space-x-6 sm:space-x-8 md:space-x-10  pb-6 px-6 ">
                    @foreach ($items as $item)
                        <form method="POST" action="{{ route($route, $item) }}" class="m-5 bg-slate-700 rounded-xl shadow-md group animate__animated animate__fadeInUp flex-none" style="animation-delay: {{ $loop->index * 0.15 }}s;">
                            @csrf
                            <button type="submit" class="bg-dark text-left focus:outline-none focus:ring-4 focus:ring-slate-500 focus:ring-offset-4 focus:ring-offset-white rounded-xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl hover:shadow-slate-500/50 hover:ring-2 hover:ring-slate-300/50">
                                <div class="relative  rounded-xl overflow-hidden shadow-2xl transform transition-all duration-500 w-24 h-24 sm:w-36 sm:h-36 md:w-48 md:h-48 lg:w-60 lg:h-60">
                                    <!-- Team Image or Animated Placeholder -->
                                    <div class="w-full h-full relative overflow-hidden">
                                        @if($item->profile_photo_path)
                                            <!-- Team Image -->
                                            <img style="object-fit: scale-down;" src="{{ $item->profile_photo_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover animate__animated animate__fadeIn transition-opacity duration-300">
                                        @else
                                            <!-- Placeholder -->
                                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                                <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 text-slate-400 opacity-80 group-hover:text-slate-200 transition-colors duration-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

  

                                    <!-- Static title at bottom -->
                                    <div class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 md:p-4 bg-gradient-to-t from-black/90 to-transparent animate__animated animate__fadeIn animate__delay-1s">
                                        <h3 class="text-white font-semibold text-sm sm:text-base md:text-lg truncate">{{ $item->name }}</h3>
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
                            <svg class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 text-gray-400 mx-auto mb-6 animate__animated animate__pulse animate__delay-2s" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-white text-xl sm:text-2xl font-bold mb-4 animate__animated animate__fadeInUp animate__delay-1s">Aucune application trouvée</h3>
                        <p class="text-gray-400 text-base sm:text-lg animate__animated animate__fadeInUp animate__delay-1s">Vous n'avez accès à aucune application pour le moment.</p>
                    </div>
                </div>
            @endif
 

</x-app-layout>
