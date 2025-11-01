<a
  href="{{ route('application.admin.configuration.credits', ['team' => $team, 'team_name' => $team->name]) }}"
  class="group relative flex flex-col justify-between overflow-hidden rounded-2xl border border-indigo-200 bg-white p-6 shadow-md backdrop-blur-lg transition-all hover:-translate-y-1 hover:shadow-xl dark:border-indigo-500 dark:bg-slate-800"
>
  <div class="absolute inset-0 -z-10">
    <div class="absolute -left-10 top-10 h-28 w-28 rounded-full bg-yellow-400 blur-3xl transition-all duration-700 group-hover:scale-105" style="opacity:.25;"></div>
    <div class="absolute -bottom-12 right-0 h-24 w-24 rounded-full bg-indigo-400 blur-3xl transition-all duration-700 group-hover:scale-110" style="opacity:.20;"></div>
  </div>

  <div class="relative flex items-start justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-300">
        {{ __('Gestion des fonds') }}
      </p>
      <h3 class="mt-2 text-lg font-semibold text-slate-900 transition-colors group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-300">
        {{ __('Credit de l equipe') }}
      </h3>
    </div>
    <span class="material-symbols-outlined text-3xl text-indigo-500 transition-transform duration-300 group-hover:rotate-12 group-hover:text-indigo-400 dark:text-indigo-300">
      credit_score
    </span>
  </div>

  <ul class="mt-5 space-y-2 text-sm text-slate-600 dark:text-slate-400">
    <li class="flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-indigo-400 dark:text-indigo-300">trending_up</span>
      {{ __('Historique des recharges et suivi des montants') }}
    </li>
    <li class="flex items-center gap-2">
      <span class="material-symbols-outlined text-base text-indigo-400 dark:text-indigo-300">assignment</span>
      {{ __('Trace des operations ajoutees par les administrateurs') }}
    </li>
  </ul>

  <div class="mt-6 flex items-center text-xs font-medium text-indigo-600 transition-colors group-hover:text-indigo-500 dark:text-indigo-300 dark:group-hover:text-indigo-200">
    {{ __('Acceder a la gestion du credit') }}
    <span class="material-symbols-outlined ml-2 text-base transition-transform duration-300 group-hover:translate-x-1">arrow_outward</span>
  </div>
</a>
