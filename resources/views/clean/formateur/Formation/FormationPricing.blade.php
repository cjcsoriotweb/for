<x-app-layout>
  <!-- Formation Pricing Page -->
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div
        class="bg-gradient-to-br from-emerald-50 to-green-50 overflow-hidden shadow-sm sm:rounded-xl border border-emerald-100 mb-8">
        <div class="p-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <div class="flex-col items-center mb-4">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-200 mr-4">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Retour au tableau de bord
                </a>
                <div class="h-6 w-px bg-gray-300 mx-4"></div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                  <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                  </svg>
                  Tarification - {{ $formation->title }}
                </h1>
              </div>
              <p class="text-gray-700 text-lg leading-relaxed">
                Configurez le prix et les options de paiement de votre formation
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Pricing Form -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
          <form id="pricingForm" action="{{ route('formateur.formation.pricing.update', $formation) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Current Price Display -->
            <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900 mb-2">Prix actuel</h3>
                  <div class="flex items-baseline">
                    <span class="text-3xl font-bold text-gray-900">
                      {{ $formation->money_amount ? number_format($formation->money_amount, 2, ',', ' ') : '0,00' }} €
                    </span>
                    @if($formation->money_amount > 0)
                    <span class="ml-2 text-sm text-gray-600">(Formation payante)</span>
                    @else
                    <span class="ml-2 text-sm text-emerald-600">(Formation gratuite)</span>
                    @endif
                  </div>
                </div>
                @if($formation->money_amount > 0)
                <div class="text-right">
                  <div class="text-sm text-gray-500">Statut</div>
                  <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                    </svg>
                    Payante
                  </div>
                </div>
                @else
                <div class="text-right">
                  <div class="text-sm text-gray-500">Statut</div>
                  <div
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                    </svg>
                    Gratuite
                  </div>
                </div>
                @endif
              </div>
            </div>

            <!-- Pricing Options -->
            <div class="mb-8">
              <label class="block text-lg font-semibold text-gray-900 mb-4">
                Options de tarification
              </label>
              <div class="space-y-4">
                <!-- Free Option -->
                <label
                  class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-emerald-300 transition-colors duration-200 cursor-pointer">
                  <input type="radio" name="pricing_type" value="free" {{ !$formation->money_amount ? 'checked' : '' }}
                  class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500 focus:ring-2">
                  <div class="ml-4 flex-1">
                    <div class="flex items-center">
                      <svg class="w-6 h-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      <div>
                        <h4 class="text-lg font-semibold text-gray-900">Formation gratuite</h4>
                        <p class="text-gray-600">La formation est accessible sans frais pour tous les utilisateurs</p>
                      </div>
                    </div>
                  </div>
                  <div class="text-emerald-600 font-semibold">Gratuit</div>
                </label>

                <!-- Paid Option -->
                <label
                  class="flex items-center p-4 border-2 border-gray-200 rounded-xl hover:border-blue-300 transition-colors duration-200 cursor-pointer">
                  <input type="radio" name="pricing_type" value="paid" {{ $formation->money_amount > 0 ? 'checked' : ''
                  }}
                  class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2">
                  <div class="ml-4 flex-1">
                    <div class="flex items-center">
                      <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                      </svg>
                      <div>
                        <h4 class="text-lg font-semibold text-gray-900">Formation payante</h4>
                        <p class="text-gray-600">Les utilisateurs doivent payer pour accéder à cette formation</p>
                      </div>
                    </div>
                  </div>
                  <div class="text-blue-600 font-semibold">Payant</div>
                </label>
              </div>
            </div>

            <!-- Price Input (shown when paid is selected) -->
            <div class="mb-8 pricing-field" style="display: {{ $formation->money_amount > 0 ? 'block' : 'none' }};">
              <label for="money_amount" class="block text-lg font-semibold text-gray-900 mb-3">
                Prix de la formation (€)
              </label>
              <div class="relative">
                <input type="number" id="money_amount" name="money_amount" min="0" step="0.01"
                  value="{{ old('money_amount', $formation->money_amount ?? 0) }}"
                  class="w-full px-4 py-3 text-lg border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 pr-12"
                  placeholder="0.00" />
                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                  <span class="text-gray-500 text-lg">€</span>
                </div>
              </div>
              @error('money_amount')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
              <p class="mt-2 text-sm text-gray-600">
                Entrez le prix en euros. Utilisez le point comme séparateur décimal (ex: 29.99)
              </p>
            </div>



            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <a href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center px-6 py-3 text-lg font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-xl transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Annuler
              </a>

              <div class="flex space-x-4">
                <button type="button" onclick="resetToFree()"
                  class="inline-flex items-center px-6 py-3 text-lg font-medium text-emerald-700 hover:text-white hover:bg-emerald-600 bg-emerald-50 border-emerald-200 hover:border-emerald-600 rounded-xl transition-all duration-200 border">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  Rendre gratuit
                </button>

                <button type="submit"
                  class="inline-flex items-center px-8 py-3 text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  Enregistrer la tarification
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Display success message -->
    @if(session('success'))
    <div id="successMessage"
      class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
      <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        {{ session("success") }}
      </div>
    </div>
    @endif

    <script>
      // Handle pricing type changes
      document.querySelectorAll('input[name="pricing_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
          const priceField = document.querySelector('.pricing-field');

          if (this.value === 'free') {
            priceField.style.display = 'none';
            document.getElementById('money_amount').value = '0';
          } else {
            priceField.style.display = 'block';
          }
        });
      });

      // Handle price input changes
      document.getElementById('money_amount').addEventListener('input', function () {
        const value = parseFloat(this.value) || 0;

        if (value > 0) {
          // Auto-select paid option if price > 0
          document.querySelector('input[name="pricing_type"][value="paid"]').checked = true;
          document.querySelector('.pricing-field').style.display = 'block';
        } else {
          // Auto-select free option if price = 0
          document.querySelector('input[name="pricing_type"][value="free"]').checked = true;
          document.querySelector('.pricing-field').style.display = 'none';
        }
      });

      function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
          };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      }

      function resetToFree() {
        if (confirm('Êtes-vous sûr de vouloir rendre cette formation gratuite ? Le prix sera remis à 0€.')) {
          document.querySelector('input[name="pricing_type"][value="free"]').checked = true;
          document.getElementById('money_amount').value = '0';
          document.querySelector('.pricing-field').style.display = 'none';
        }
      }

      // Handle form submission
      document.getElementById("pricingForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        // Ensure money_amount is set correctly based on pricing type
        const pricingType = document.querySelector('input[name="pricing_type"]:checked').value;
        const moneyAmountField = document.getElementById('money_amount');

        if (pricingType === 'free') {
          formData.set('money_amount', '0');
        } else {
          const moneyValue = parseFloat(moneyAmountField.value) || 0;
          formData.set('money_amount', moneyValue.toString());
        }

        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML =
          '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Enregistrement...';

        fetch(this.action, {
          method: "POST",
          body: formData,
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
          },
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Redirect to dashboard with success message
              window.location.href = "{{ route('formateur.formation.show', $formation) }}";
            } else {
              throw new Error(data.message || "Erreur lors de la modification");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert('Erreur lors de la modification: ' + error.message);
          })
          .finally(() => {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
          });
      });

      // Auto-hide success message after 5 seconds
      const successMessage = document.getElementById("successMessage");
      if (successMessage) {
        setTimeout(() => {
          successMessage.style.opacity = "0";
          setTimeout(() => {
            successMessage.remove();
          }, 300);
        }, 5000);
      }
    </script>
</x-app-layout>