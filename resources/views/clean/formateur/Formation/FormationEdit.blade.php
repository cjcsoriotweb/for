<x-app-layout>
  <!-- Formation Edit Page -->
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div
        class="bg-gradient-to-br from-indigo-50 to-blue-50 overflow-hidden shadow-sm sm:rounded-xl border border-indigo-100 mb-8">
        <div class="p-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <div class="flex items-center mb-4">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-200 mr-4">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Retour au tableau de bord
                </a>
                <div class="h-6 w-px bg-gray-300 mx-4"></div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                  <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                  </svg>
                  Modifier la formation - {{ $formation->title }}
                </h1>
              </div>
              <p class="text-gray-700 text-lg leading-relaxed">
                Modifiez les informations de votre formation
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Form -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
          <form action="{{ route('formateur.formation.update', $formation) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Formation Title -->
            <div class="mb-6">
              <label for="title" class="block text-lg font-semibold text-gray-900 mb-3">
                Titre de la formation
              </label>
              <input type="text" id="title" name="title" value="{{ old('title', $formation->title) }}"
                class="w-full px-4 py-3 text-lg border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                placeholder="Entrez le titre de la formation" required />
              @error('title')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Formation Description -->
            <div class="mb-6">
              <label for="description" class="block text-lg font-semibold text-gray-900 mb-3">
                Description de la formation
              </label>
              <textarea id="description" name="description" rows="6"
                class="w-full px-4 py-3 text-lg border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200"
                placeholder="Entrez la description de la formation"
                required>{{ old('description', $formation->description) }}</textarea>
              @error('description')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>



            <!-- Formation Status -->
            <div class="mb-8">
              <label class="block text-lg font-semibold text-gray-900 mb-4">
                Statut de la formation
              </label>
              <div class="flex items-center space-x-6">
                <label class="flex items-center">
                  <input type="checkbox" name="active" value="1" {{ old('active', $formation->active) ? 'checked' : ''
                  }}
                  class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                  <span class="ml-3 text-lg text-gray-700">
                    Formation active (visible pour les élèves)
                  </span>
                </label>
              </div>
              @error('active')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
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
                <button type="button" onclick="toggleFormationStatus()"
                  class="inline-flex items-center px-6 py-3 text-lg font-medium {{ $formation->active ? 'text-red-700 hover:text-white hover:bg-red-600 bg-red-50 border-red-200 hover:border-red-600' : 'text-emerald-700 hover:text-white hover:bg-emerald-600 bg-emerald-50 border-emerald-200 hover:border-emerald-600' }} rounded-xl transition-all duration-200 border">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($formation->active)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                    @endif
                  </svg>
                  {{ $formation->active ? 'Désactiver' : 'Activer' }} maintenant
                </button>

                <button type="submit"
                  class="inline-flex items-center px-8 py-3 text-lg font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  Enregistrer les modifications
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
      function toggleFormationStatus() {
        const formationId = {{ $formation-> id
      }};
      const currentStatus = {{ $formation-> active ? 'true' : 'false' }};

      // Show confirmation dialog
      const action = currentStatus ? 'désactiver' : 'activer';
      const confirmMessage = 'Êtes-vous sûr de vouloir ' + action + ' cette formation ?';

      if (!confirm(confirmMessage)) {
        return;
      }

      // Disable the button and show loading state
      const button = event.target.closest('button');
      const originalText = button.innerHTML;

      button.disabled = true;
      button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';

      // Make AJAX request to toggle status
      fetch(`/formateur/formation/${formationId}/toggle-status`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update the UI
            location.reload(); // Simple reload to reflect changes
          } else {
            throw new Error(data.message || 'Erreur lors du changement de statut');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Erreur lors du changement de statut: ' + error.message);
        })
        .finally(() => {
          // Re-enable button and restore original text
          button.disabled = false;
          button.innerHTML = originalText;
        });
      }

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