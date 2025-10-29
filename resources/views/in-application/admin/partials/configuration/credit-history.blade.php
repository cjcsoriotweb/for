<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                {{ __('Historique des fonds') }}
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Consultez toutes les operations de credit effectuees sur cette equipe.') }}
            </p>
        </div>
        <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-600 dark:text-emerald-300">
            {{ __('Solde actuel : :amount EUR', ['amount' => number_format((int) $team->money, 0, ',', ' ')]) }}
        </span>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-900/60">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('Date') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('Montant') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('Raison') }}
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('Ajoute par') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse ($transactions as $transaction)
                    <tr class="bg-white transition hover:bg-slate-50 dark:bg-slate-800 dark:hover:bg-slate-700/60">
                        <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-200">
                            {{ optional($transaction->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-emerald-600 dark:text-emerald-300">
                            +{{ number_format((int) $transaction->amount, 0, ',', ' ') }} EUR
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                            {{ $transaction->reason }}
                        </td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                            {{ $transaction->actor->name ?? __('Systeme') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Aucune operation enregistree pour le moment.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

