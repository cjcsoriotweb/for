<x-organisateur-layout :team="$team">

  {{-- Header --}}
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <x-organisateur.parts.breadcrumb :team="$team" :items="[
        ['label' => 'Accueil', 'url' => route('organisateur.index', $team)],
        ['label' => 'Recharge du solde', 'url' => route('organisateur.recharge.show', $team)],
        ['label' => 'Confirmation', 'url' => null]
      ]" />

      <div class="mt-6 text-center">
        <div
          class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900 dark:to-teal-900 mb-6">
          <svg class="h-10 w-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          Paiement réussi !
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          Votre solde a été rechargé avec succès
        </p>
      </div>
    </div>

    {{-- Success Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8">
      <div class="p-8">
        <div class="text-center mb-8">
          <div
            class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border border-emerald-200 dark:border-gray-700 mb-6">
            <div class="flex items-center justify-center mb-4">
              <div
                class="flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                  </path>
                </svg>
              </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
              +{{ number_format($amount, 2, ',', ' ') }} €
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
              Ajouté à votre solde
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- New Balance --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Nouveau solde</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($team->money, 0, ',', ' ') }}
                €</p>
            </div>

            {{-- Transaction Date --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Date de transaction</p>
              <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ now()->format('d/m/Y à H:i') }}</p>
            </div>
          </div>
          </div>

          {{-- Payment Details --}}
          @if($payment ?? false)
          <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
              Détails du paiement
            </h3>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                  <p class="text-gray-600 dark:text-gray-400">ID de transaction</p>
                  <p class="font-mono text-gray-900 dark:text-white">{{ $payment->provider_session_id }}</p>
                </div>
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Montant</p>
                  <p class="text-gray-900 dark:text-white">{{ number_format(($payment->amount ?? 0) / 100, 2, ',', ' ') }}
                    €</p>
                </div>
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Statut</p>
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                    {{ ucfirst($payment->status) }}
                  </span>
                </div>
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Méthode de paiement</p>
                  @php
                    $method = data_get($payment->provider_payload, 'payment_method_types.0')
                      ?? ($session->payment_method_types[0] ?? 'Carte bancaire');
                  @endphp
                  <p class="text-gray-900 dark:text-white capitalize">{{ $method }}</p>
                </div>
                @if($payment->paid_at)
                <div>
                  <p class="text-gray-600 dark:text-gray-400">Date du paiement</p>
                  <p class="text-gray-900 dark:text-white">{{ $payment->paid_at->format('d/m/Y à H:i') }}</p>
                </div>
                @endif
              </div>
            </div>
          </div>
          @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="{{ route('organisateur.index', $team) }}"
        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 transition-all">
        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
          </path>
        </svg>
        Retour à l'accueil
      </a>

      <a href="{{ route('organisateur.recharge.show', $team) }}"
        class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Recharger à nouveau
      </a>
    </div>
  </div>

</x-organisateur-layout>
