<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
        <div class="max-w-sm space-y-3">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                {{ __('Ajouter des fonds a cette equipe') }}
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Solde actuel : :amount EUR', ['amount' => number_format((int) $team->money, 0, ',', ' ')]) }}
            </p>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Chaque operation est historisee avec le montant, la raison et la personne qui effectue la recharge.') }}
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('application.admin.configuration.credit', $team) }}"
            class="w-full max-w-md space-y-4 rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900"
        >
            @csrf
            <input type="hidden" name="team_id" value="{{ $team->id }}" />

            <div>
                <label for="montant" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Montant (EUR)') }}
                </label>
                <input
                    type="number"
                    name="montant"
                    id="montant"
                    min="1"
                    step="1"
                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    placeholder="500"
                    required
                />
            </div>

            <div>
                <label for="raison" class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Raison de la recharge') }}
                </label>
                <input
                    type="text"
                    name="raison"
                    id="raison"
                    class="mt-2 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    placeholder="{{ __('Exemple : Budget supplementaire Q4') }}"
                    required
                />
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:focus:ring-emerald-600"
            >
                <span class="material-symbols-outlined text-base">add</span>
                {{ __('Valider la recharge') }}
            </button>
        </form>
    </div>
</div>

