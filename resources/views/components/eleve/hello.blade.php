<section
    class="bg-gradient-to-br from-primary via-blue-600 to-blue-700 text-white"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-8 md:mb-0">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">
                    Bonjour {{ Auth::user()->name }} ! 👋
                </h1>
                <p class="text-xl text-blue-100 mb-6">
                    Prêt à continuer votre apprentissage ?
                </p>
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            { $eleve->formationsCount }
                        </div>
                        <div class="text-sm text-blue-200">
                            Formations en cours
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">67%</div>
                        <div class="text-sm text-blue-200">
                            Progression globale
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">12</div>
                        <div class="text-sm text-blue-200">
                            Certificats obtenus
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center"
            >
                <svg
                    class="w-16 h-16 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    ></path>
                </svg>
            </div>
        </div>
    </div>
</section>
