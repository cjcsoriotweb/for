<div>
    <!-- Titre -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Créer votre compte</h1>
        <p class="text-gray-600">Rejoignez notre plateforme en quelques étapes simples</p>
    </div>

    <!-- Contenu des étapes -->
    <div class="space-y-8" wire:key="step-content-{{ $currentStep }}">
        @if ($currentStep === 1)
            <div wire:key="step-1" class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Nom complet
                </label>
                <div class="relative">
                    <input
                        wire:model.defer="name"
                        type="text"
                        id="name"
                        placeholder="Votre nom complet"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                    />
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endif

        @if ($currentStep === 2)
            <div wire:key="step-2" class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Adresse email
                </label>
                <div class="relative">
                    <input
                        wire:model.defer="email"
                        type="email"
                        id="email"
                        placeholder="votre@email.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                    />
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endif

        @if ($currentStep === 3)
            <div wire:key="step-3" class="space-y-4">
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input
                            wire:model.defer="password"
                            type="password"
                            id="password"
                            placeholder="Votre mot de passe sécurisé"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                        />
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <input
                            wire:model.defer="password_confirmation"
                            type="password"
                            id="password_confirmation"
                            placeholder="Confirmer votre mot de passe"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                        />
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($password)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Force du mot de passe</span>
                            <span class="text-sm font-semibold {{ $this->getPasswordStrength()['color'] === 'red' ? 'text-red-600' : ($this->getPasswordStrength()['color'] === 'yellow' ? 'text-yellow-600' : ($this->getPasswordStrength()['color'] === 'blue' ? 'text-blue-600' : 'text-green-600')) }}">
                                {{ $this->getPasswordStrength()['text'] }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $this->getPasswordStrength()['color'] === 'red' ? 'bg-red-500' : ($this->getPasswordStrength()['color'] === 'yellow' ? 'bg-yellow-500' : ($this->getPasswordStrength()['color'] === 'blue' ? 'bg-blue-500' : 'bg-green-500')) }}" style="width: {{ $this->getPasswordStrength()['percentage'] }}%"></div>
                        </div>
                        <div class="mt-2 text-xs text-gray-600">
                            <p>Le mot de passe doit contenir au moins 12 caractères avec majuscules, minuscules, chiffres et caractères spéciaux.</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if ($currentStep === 4)
            <div wire:key="step-4" class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-xl border border-indigo-100">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Conditions d'utilisation</h3>
                        <p class="text-sm text-gray-700 mb-4">
                            En cliquant sur "J'accepte", vous acceptez nos conditions d'utilisation et notre politique de confidentialité. Vous vous engagez à utiliser la plateforme de manière responsable.
                        </p>
                        <a target="_blank" href="{{ route('guest.terms') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Lire les conditions complètes
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Boutons de navigation -->
    <div class="flex justify-between mt-10 space-x-4" wire:key="nav-buttons-{{ $currentStep }}">
        @if ($currentStep > 1)
            <button type="button"
                    wire:click="previousStep"
                    class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Précédent</span>
            </button>
        @else
            <div class="flex-1"></div>
        @endif

        @if ($currentStep < $totalSteps)
            <button type="button"
                    wire:click="nextStep"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 font-medium flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span>Suivant</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @elseif ($currentStep === $totalSteps)
            <button type="button"
                    wire:click="acceptRegister"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 font-medium flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>J'accepte les conditions</span>
            </button>
        @endif
    </div>

    <!-- Étapes de progression -->
    <div class="mt-12" wire:key="progress-bar-{{ $currentStep }}">
        <div class="flex items-center justify-center space-x-8">
            <!-- Étape actuelle -->
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold bg-indigo-600 text-white shadow-lg ring-4 ring-indigo-200">
                    {{ $currentStep }}
                </div>
                <span class="mt-3 text-sm font-medium text-indigo-600">
                    @switch($currentStep)
                        @case(1) Nom complet @break
                        @case(2) Email @break
                        @case(3) Mot de passe @break
                        @case(4) Conditions @break
                    @endswitch
                </span>
            </div>

            @if ($currentStep < $totalSteps)
                <!-- Ligne de connexion -->
                <div class="w-20 h-1 rounded-full bg-gray-300"></div>

                <!-- Étape suivante -->
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600">
                        {{ $currentStep + 1 }}
                    </div>
                    <span class="mt-3 text-sm font-medium text-gray-500">
                        @switch($currentStep + 1)
                            @case(2) Email @break
                            @case(3) Mot de passe @break
                            @case(4) Conditions @break
                        @endswitch
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>
