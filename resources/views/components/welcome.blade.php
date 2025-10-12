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

<style>
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
.welcome-screen { animation: fadeInUp 1s ease-out; }
</style>
