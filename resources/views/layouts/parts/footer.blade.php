    <footer class="bg-gradient-to-r from-white via-gray-50 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 border-t border-gray-200 dark:border-gray-800 py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-6">
          <x-application-logo class="h-12 w-auto" size="5xl"/>

          <p class="text-center text-sm text-gray-600 dark:text-gray-300 max-w-prose">
            {{ __("Construisons des choses élégantes et utiles — restez informé·e de nos nouveautés.") }}
          </p>

          <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-6 items-center">
            <nav class="flex justify-center space-x-6">
              <a href="{{ route('guest.policy') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                {{ __("Mentions légales") }}
              </a>
              <a href="#" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                {{ __("Confidentialité") }}
              </a>
              <a href="#" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                {{ __("Contact") }}
              </a>
            </nav>

            <div class="flex justify-center space-x-4">
              <a href="#" aria-label="GitHub" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 .5a12 12 0 00-3.8 23.4c.6.1.8-.2.8-.5v-2c-3.3.7-4-1.6-4-1.6-.5-1.2-1.2-1.5-1.2-1.5-1-.7.1-.7.1-.7 1.1.1 1.6 1.2 1.6 1.2 1 .1 1.6-.8 1.9-1.2-2.6-.3-5.3-1.3-5.3-5.8 0-1.3.5-2.4 1.2-3.3-.1-.3-.5-1.6.1-3.3 0 0 1-.3 3.3 1.2a11.4 11.4 0 016 0C17.6 4 18.6 4.3 18.6 4.3c.6 1.7.2 3 .1 3.3.8.9 1.2 2 1.2 3.3 0 4.5-2.7 5.5-5.3 5.8.7.6 1.2 1.6 1.2 3.1v4.6c0 .3.2.6.8.5A12 12 0 0012 .5z"/>
                </svg>
              </a>
              <a href="#" aria-label="Twitter" class="text-blue-500 hover:text-blue-700">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M23 3a10.9 10.9 0 01-3.1.9A4.5 4.5 0 0022.4 2a9 9 0 01-2.8 1.1A4.5 4.5 0 0016.6 2c-2.5 0-4.5 2.2-3.9 4.6A12.8 12.8 0 013 3s-4 9 5 13a13 13 0 01-8 2c9 5 20 0 20-11.5v-.5A7.5 7.5 0 0023 3z"/>
                </svg>
              </a>
              <a href="#" aria-label="LinkedIn" class="text-blue-700 hover:text-blue-900">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M4.98 3.5A2.5 2.5 0 002.5 6v12a2.5 2.5 0 002.48 2.5h.02A2.5 2.5 0 007.98 18V6a2.5 2.5 0 00-2.5-2.5zM9 8h3v10H9zM11.5 3.5A1.5 1.5 0 1110 5a1.5 1.5 0 011.5-1.5zM14.5 8h2.75v1.36h.04c.38-.72 1.3-1.36 2.68-1.36 2.86 0 3.4 1.88 3.4 4.33V18h-3V12.3c0-1.34-.02-3.06-1.87-3.06-1.87 0-2.16 1.47-2.16 2.98V18h-3V8z"/>
                </svg>
              </a>
            </div>
          </div>

          <div class="w-full border-t border-gray-200 dark:border-gray-800 mt-6 pt-6 text-sm text-gray-600 dark:text-gray-400 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>© {{ now()->year }} {{ __("Tous droits réservés") }} <span class="font-semibold">{{ config('app.name') }}</span></p>
            <p class="text-xs">{{ __("Conçu avec soin • Accessible • Responsive") }}</p>
          </div>
        </div>
      </div>
    </footer>