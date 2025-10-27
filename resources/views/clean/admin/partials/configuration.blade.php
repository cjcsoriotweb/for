<div
  class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:bg-opacity-20 dark:to-teal-900 dark:bg-opacity-20 rounded-2xl p-8 border border-emerald-200 dark:border-emerald-800">
  <div class="flex items-center space-x-6">
    <div
      class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
      <span class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400">bolt</span>
    </div>
    <div class="flex-1">
      <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-200 mb-2">
        {{ __("Actions rapides") }}
      </h3>
    </div>
    <div class="hidden md:block">
      <div class="flex space-x-3">
        <a href="{{
                        route('eleve.index', ['team' => $team, 'team_name' => $team->name])
                    }}"
          class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl shadow-sm transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
          </svg>

          Eleve
        </a>
        <a href="{{
                        route('organisateur.index', ['team' => $team])
                    }}"
          class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>

          Organisateur
        </a>



        <a href="{{
                        route('formateur.home', ['team' => $team])
                    }}"
          class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl shadow-sm transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
          </svg>

          Formateur
        </a>

        <a href="{{
                        route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name])
                    }}"
          class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-sm transition-colors">
          <span class="material-symbols-outlined mr-2 text-sm">settings</span>
          Configuration
        </a>
      </div>
    </div>
  </div>
</div>