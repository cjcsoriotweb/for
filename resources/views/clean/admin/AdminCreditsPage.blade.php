<x-admin.layout
    :team="$team"
    icon="credit_score"
    :title="__('Gestion du credit')"
    :subtitle="__('Ajoutez des fonds et consultez l historique des operations de votre equipe.')"
>
    @include('clean.admin.partials.configuration.add-credit', ['team' => $team])

    <div class="mt-10">
        @include('clean.admin.partials.configuration.credit-history', [
            'transactions' => $transactions,
            'team' => $team,
        ])
    </div>

    <div class="mt-10">
        @include('clean.admin.partials.home-button', ['team' => $team])
    </div>
</x-admin.layout>

