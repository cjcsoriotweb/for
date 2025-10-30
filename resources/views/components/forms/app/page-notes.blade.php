<form
    id="page-notes-form"
    class="mt-4 space-y-3"
    {{ $attributes }}
>
    <div>
        <label
            for="page-notes-title"
            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
        >
            {{ __('Titre (optionnel)') }}
        </label>
        <input
            id="page-notes-title"
            name="title"
            type="text"
            class="mt-1 w-full rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
        />
    </div>

    <div>
        <label
            for="page-notes-content"
            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
        >
            {{ __('Votre note *') }}
        </label>
        <textarea
            id="page-notes-content"
            name="content"
            rows="4"
            required
            class="mt-1 w-full rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
        ></textarea>
    </div>

    <div class="flex items-center justify-between space-x-2">
        <button
            type="button"
            id="page-notes-cancel"
            class="hidden rounded-md border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
        >
            {{ __('Annuler') }}
        </button>
        <button
            type="submit"
            id="page-notes-submit"
            class="ml-auto inline-flex items-center space-x-1 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
        >
            <span class="material-symbols-outlined text-base">add</span>
            <span>{{ __('Ajouter') }}</span>
        </button>
    </div>
</form>
