<a
    href="{{ route('application.admin.users.index', $team) }}"
    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
>
    <div class="flex items-center space-x-4">
        <div
            class="w-12 h-12 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center"
        >
            <span
                class="material-symbols-outlined text-blue-600 dark:text-blue-400"
                >people</span
            >
        </div>
        <div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ count($team->allUsers() )-1 }}
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Utilisateurs dans cette application
            </div>
        </div>
    </div>
</a>
