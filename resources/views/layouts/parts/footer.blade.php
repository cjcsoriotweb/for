    <footer class="bg-gradient-to-r from-white via-gray-50 to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 border-t border-gray-200 dark:border-gray-800 py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center gap-6">
          <x-application-logo class="h-12 w-auto" size="5xl"/>

          <p class="text-center text-sm text-gray-600 dark:text-gray-300 max-w-prose">
            {{ __("Construisons des choses élégantes et utiles restez informé·e de nos nouveautés.") }}
            <br>
       
          </p>

          <div class="w-full  sm:grid-cols-2 gap-6 items-center">
            <nav class="flex justify-around space-x-6">
              <a href="{{ route('guest.policy') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                {{ __("Mentions légales") }}
              </a>
            
                   <a href="mailto:julien.soriot@evolubat-academy.fr" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                    <b>julien.soriot@evolubat-academy.fr</b>
                   </a>
              <a href="{{ route('user.tickets') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                {{ __("Contact & Aide") }}
              </a>
         
            </nav>

          </div>

          <div class="w-full border-t border-gray-200 dark:border-gray-800 mt-6 pt-6 text-sm text-gray-600 dark:text-gray-400 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p>© {{ now()->year }} {{ __("Tous droits réservés") }} <span class="font-semibold">{{ config('app.name') }}</span></p>
           
          </div>
        </div>
      </div>
    </footer>