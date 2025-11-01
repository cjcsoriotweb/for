    <footer class="border-t border-gray-200 dark:border-gray-800 py-12">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col space-y-4 text-center">
        <x-application-logo class=" flex items-center justify-center " size="4xl"/>
       

        <p>
          <a href="{{ route('guest.policy') }}" 
          class="text-blue-500 hover:text-blue-700">
          {{ __("Mentions légales")
            }}</a>
        </p>

              <p
              class="text-blue-800">
          © {{ now()->year }} {{ __("Tous droits réservés ") }} <b>{{ config("app.name") }}</b>
            
        </p>
      </div>
    </footer>