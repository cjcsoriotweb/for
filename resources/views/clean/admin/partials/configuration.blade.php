<div
    class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900 dark:bg-opacity-20 dark:to-teal-900 dark:bg-opacity-20 rounded-2xl p-8 border border-emerald-200 dark:border-emerald-800"
>
    <div class="flex items-center space-x-6">
        <div
            class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center"
        >
            <span
                class="material-symbols-outlined text-3xl text-emerald-600 dark:text-emerald-400"
                >bolt</span
            >
        </div>
        <div class="flex-1">
            <h3
                class="text-xl font-bold text-emerald-800 dark:text-emerald-200 mb-2"
            >
                {{ __("Actions rapides") }}
            </h3>
        </div>
        <div class="hidden md:block">
            <div class="flex space-x-3">
                <a
                    href="{{
                        route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name])
                    }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl shadow-sm transition-colors"
                >
                    <span class="material-symbols-outlined mr-2 text-sm"
                        >settings</span
                    >
                    Configuration
                </a>
            </div>
        </div>
    </div>
</div>
