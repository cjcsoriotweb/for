<a href="{{ route('application.admin.configuration.index', ['team'=> $team, 'team_name' => $team->name])
  }}" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
  <div class="flex items-center space-x-4">
    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5" />
      </svg>

    </div>
    <div>
      <div class="text-sm text-slate-600 dark:text-slate-400">
        Configuration
      </div>
    </div>
  </div>
</a>