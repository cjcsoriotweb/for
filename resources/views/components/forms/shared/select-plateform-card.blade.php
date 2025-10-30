@props([
    'item',
    'route',
    'delay' => 0,
])

@php
    $animationDelay = is_numeric($delay) ? $delay : 0;
@endphp

<form
    method="POST"
    action="{{ route($route, $item) }}"
    class="bg-white rounded-2xl shadow-lg group animate__animated animate__fadeInUp flex-none"
    style="animation-delay: {{ $animationDelay }}s;"
    {{ $attributes }}
>
    @csrf

    <button
        type="submit"
        class="text-left focus:outline-none focus:ring-4 focus:ring-red-500 focus:ring-offset-4 focus:ring-offset-gray-900 rounded-xl overflow-hidden transform transition-all duration-500 hover:scale-105 hover:rotate-1 hover:z-20 hover:shadow-2xl hover:shadow-red-500/30 animate__animated animate__pulse animate__infinite animate__slower"
    >
        <div class="relative rounded-xl overflow-hidden shadow-2xl transform transition-all duration-500 w-32 h-32 sm:w-48 sm:h-48 md:w-64 md:h-64 lg:w-80 lg:h-80">
            <div class="w-full h-full relative overflow-hidden">
                @if($item->profile_photo_path)
                    <img
                        src="{{ $item->profile_photo_url }}"
                        style="object-fit: scale-down;"
                        alt="{{ $item->name }}"
                        class="w-full h-full object-cover animate__animated animate__fadeIn transition-transform duration-500 group-hover:scale-110"
                    >
                @else
                    <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center relative">
                        <div class="relative z-10 animate__animated animate__zoomIn animate__delay-1s">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 text-gray-300 opacity-80 group-hover:text-white transition-colors duration-300 animate__animated animate__bounce animate__delay-2s" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="absolute inset-0 pointer-events-none">
                            @for ($i = 0; $i < 5; $i++)
                                <div
                                    class="absolute w-2 h-2 bg-white/20 rounded-full animate__animated animate__fadeIn animate__infinite animate__slower"
                                    style="top: {{ rand(10, 90) }}%; left: {{ rand(10, 90) }}%; animation-delay: {{ $i * 0.5 }}s; animation-duration: {{ rand(3, 6) }}s;"
                                ></div>
                            @endfor
                        </div>
                    </div>
                @endif
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 md:p-4 bg-gradient-to-t from-black/90 to-transparent animate__animated animate__fadeIn animate__delay-1s">
                <h3 class="text-white font-semibold text-sm sm:text-base md:text-lg truncate">{{ $item->name }}</h3>
            </div>
        </div>
    </button>
</form>
