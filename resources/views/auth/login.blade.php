<x-guest-layout>
    <x-ui::auth.form-page
        title="Bienvenue"
        subtitle="Connectez-vous à votre compte"
        :action="route('login')"
        submit-label="Se connecter"
        :remember="true"
        remember-label="Se souvenir de moi"
        :remember-checked="old('remember')"
        :forgot-password-url="Route::has('password.request') ? route('password.request') : null"
        forgot-password-label="Mot de passe oublié ?"
        footer-text="Vous n'avez pas encore de compte ?"
        footer-link-text="S'inscrire"
        footer-link-url="{{ route('register') }}"
    >
        <x-slot name="status">
            @session('status')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ $value }}
                            </p>
                        </div>
                    </div>
                </div>
            @endsession
        </x-slot>

        <x-slot name="fields">
            <div class="space-y-1">
                <x-label for="email" value="Adresse email" class="block text-sm font-semibold text-gray-800 mb-2" />
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <x-input id="email"
                             class="relative block w-full pl-14 pr-4 py-4 border-2 border-gray-200 rounded-xl shadow-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all duration-300 hover:shadow-xl hover:border-indigo-300"
                             type="email"
                             name="email"
                             :value="old('email')"
                             required
                             autofocus
                             autocomplete="username"
                             placeholder="votre@email.com" />
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-md">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <x-label for="password" value="Mot de passe" class="block text-sm font-semibold text-gray-800 mb-2" />
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <x-input id="password"
                             class="relative block w-full pl-14 pr-14 py-4 border-2 border-gray-200 rounded-xl shadow-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all duration-300 hover:shadow-xl hover:border-indigo-300"
                             type="password"
                             name="password"
                             required
                             autocomplete="current-password"
                             placeholder="Votre mot de passe" />
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-md">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center group/toggle" onclick="togglePassword()">
                        <div class="p-2 bg-gray-100 hover:bg-indigo-100 rounded-lg transition-colors duration-200 group-hover/toggle:bg-indigo-200">
                            <svg class="h-5 w-5 text-gray-500 group-hover/toggle:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password-toggle">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </x-slot>
    </x-ui::auth.form-page>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</x-guest-layout>
