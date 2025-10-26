<x-organisateur-layout :team="$team">

  {{-- Header --}}
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <x-organisateur.parts.breadcrumb :team="$team" :items="[
        ['label' => 'Accueil', 'url' => route('organisateur.index', $team)],
        ['label' => 'Recharge du solde', 'url' => null]
      ]" />

      <div class="mt-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
          Recharger le solde
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
          Ajoutez des fonds à votre compte pour financer les formations de vos étudiants
        </p>
      </div>
    </div>

    {{-- Current Balance --}}
    <div class="mb-8">
      <div
        class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border border-emerald-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Solde actuel</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($team->money, 0, ',', ' ') }} €
            </p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
              </path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    {{-- Recharge Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
      <div class="p-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
          Montant à recharger
        </h2>

        <form id="recharge-form" class="space-y-6">
          @csrf

          {{-- Amount Selection --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
              Choisissez un montant
            </label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
              <button type="button" data-amount="1000"
                class="amount-btn p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">10 €</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Montant de base</div>
              </button>
              <button type="button" data-amount="2500"
                class="amount-btn p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">25 €</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Populaire</div>
              </button>
              <button type="button" data-amount="5000"
                class="amount-btn p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">50 €</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Recommandé</div>
              </button>
              <button type="button" data-amount="10000"
                class="amount-btn p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-emerald-500 dark:hover:border-emerald-400 transition-colors text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">100 €</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Grande quantité</div>
              </button>
            </div>

            {{-- Custom Amount --}}
            <div class="relative">
              <label for="custom-amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Ou saisissez un montant personnalisé
              </label>
              <div class="relative">
                <input type="number" id="custom-amount" name="amount" min="1" max="1000" step="0.01" placeholder="0.00"
                  class="block w-full px-4 py-3 text-lg border-gray-300 dark:border-gray-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 dark:text-gray-400 text-lg">€</span>
                </div>
              </div>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Montant minimum: 1 € - Montant maximum: 1000 €
              </p>
            </div>
          </div>

          {{-- Payment Method Info --}}
          <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                  Paiement sécurisé
                </h3>
                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                  <p>Le paiement est traité de manière sécurisée via Stripe. Vos informations de carte ne sont pas
                    stockées sur nos serveurs.</p>
                </div>
              </div>
            </div>
          </div>

          {{-- Submit Button --}}
          <div class="flex justify-end">
            <button type="submit" id="submit-btn" disabled
              class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-medium cursor-not-allowed hover:from-emerald-600 hover:to-teal-700 transition-all">
              Sélectionnez un montant
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Loading Modal --}}
  <div id="loading-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 dark:bg-emerald-900">
          <svg class="animate-spin h-6 w-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">
          Redirection vers le paiement
        </h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Veuillez patienter, vous allez être redirigé vers la page de paiement sécurisée...
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Error Modal --}}
  <div id="error-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
      <div class="mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
          <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
            </path>
          </svg>
        </div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">
          Erreur
        </h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-gray-500 dark:text-gray-400 error-message">
          </p>
        </div>
        <div class="flex justify-center mt-4">
          <button onclick="closeErrorModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Fermer
          </button>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    let selectedAmount = null;

    // Amount button handlers
    document.querySelectorAll('.amount-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        // Remove active state from all buttons
        document.querySelectorAll('.amount-btn').forEach(b => {
          b.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
          b.classList.add('border-gray-200', 'dark:border-gray-600');
        });

        // Add active state to clicked button
        this.classList.remove('border-gray-200', 'dark:border-gray-600');
        this.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');

        // Set the amount
        selectedAmount = parseInt(this.dataset.amount);
        document.getElementById('custom-amount').value = (selectedAmount / 100).toFixed(2);
        updateSubmitButton();
      });
    });

    // Custom amount input handler
    document.getElementById('custom-amount').addEventListener('input', function () {
      // Remove active state from preset buttons
      document.querySelectorAll('.amount-btn').forEach(b => {
        b.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
        b.classList.add('border-gray-200', 'dark:border-gray-600');
      });

      const amount = parseFloat(this.value);
      if (amount > 0) {
        selectedAmount = Math.round(amount * 100); // Convert to centimes
        updateSubmitButton();
      } else {
        selectedAmount = null;
        updateSubmitButton();
      }
    });

    function updateSubmitButton() {
      const submitBtn = document.getElementById('submit-btn');
      const customAmountInput = document.getElementById('custom-amount');

      if (selectedAmount && selectedAmount >= 100 && selectedAmount <= 100000) {
        submitBtn.disabled = false;
        submitBtn.className = 'px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-medium hover:from-emerald-600 hover:to-teal-700 transition-all';
        submitBtn.textContent = `Payer ${(selectedAmount / 100).toFixed(2)} €`;
      } else {
        submitBtn.disabled = true;
        submitBtn.className = 'px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg font-medium cursor-not-allowed hover:from-emerald-600 hover:to-teal-700 transition-all';
        submitBtn.textContent = 'Sélectionnez un montant';
      }
    }

    // Form submission
    document.getElementById('recharge-form').addEventListener('submit', async function (e) {
      e.preventDefault();

      if (!selectedAmount || selectedAmount < 100 || selectedAmount > 100000) {
        showError('Veuillez sélectionner un montant valide entre 1 € et 1000 €.');
        return;
      }

      const submitBtn = document.getElementById('submit-btn');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Redirection...';

      showLoadingModal();

      try {
        const response = await fetch('{{ route("organisateur.recharge.checkout", $team) }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            amount: selectedAmount
          })
        });

        const data = await response.json();

        if (data.url) {
          window.location.href = data.url;
        } else {
          throw new Error(data.error || 'Erreur lors de la création de la session de paiement.');
        }
      } catch (error) {
        hideLoadingModal();
        showError(error.message || 'Une erreur est survenue. Veuillez réessayer.');
        submitBtn.disabled = false;
        submitBtn.textContent = `Payer ${(selectedAmount / 100).toFixed(2)} €`;
      }
    });

    function showLoadingModal() {
      document.getElementById('loading-modal').classList.remove('hidden');
    }

    function hideLoadingModal() {
      document.getElementById('loading-modal').classList.add('hidden');
    }

    function showError(message) {
      document.querySelector('.error-message').textContent = message;
      document.getElementById('error-modal').classList.remove('hidden');
    }

    function closeErrorModal() {
      document.getElementById('error-modal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.getElementById('loading-modal').addEventListener('click', function (e) {
      if (e.target === this) {
        hideLoadingModal();
      }
    });

    document.getElementById('error-modal').addEventListener('click', function (e) {
      if (e.target === this) {
        closeErrorModal();
      }
    });
  </script>
  @endpush

</x-organisateur-layout>