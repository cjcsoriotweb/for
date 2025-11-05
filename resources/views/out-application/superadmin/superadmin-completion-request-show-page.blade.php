<x-superadmin-layout>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <x-superadmin.notification-messages />

    <!-- Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Demande de validation</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Formation : {{ $formationUser->formation->title }}
          </p>
        </div>
        <a href="{{ route('superadmin.completion-requests.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
          ← Retour à la liste
        </a>
      </div>
    </div>

    <!-- Request Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informations de la demande</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Étudiant</h3>
          <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                  {{ substr($formationUser->user->name, 0, 2) }}
                </span>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $formationUser->user->name }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ $formationUser->user->email }}</p>
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Équipe</h3>
          <p class="text-sm text-gray-900 dark:text-gray-100">{{ $formationUser->team->name }}</p>
        </div>

        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Date de demande</h3>
          <p class="text-sm text-gray-900 dark:text-gray-100">
            {{ $formationUser->completion_request_at->format('d/m/Y à H:i') }}
          </p>
        </div>

        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Statut</h3>
          @if($formationUser->completion_request_status === 'pending')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200">
              En attente
            </span>
          @elseif($formationUser->completion_request_status === 'approved')
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
              Approuvée
            </span>
          @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">
              Rejetée
            </span>
          @endif
        </div>
      </div>
    </div>

    <!-- Formation Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Détails de la formation</h2>

      <div class="space-y-4">
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Titre</h3>
          <p class="text-sm text-gray-900 dark:text-gray-100">{{ $formationUser->formation->title }}</p>
        </div>

        @if($formationUser->formation->description)
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
          <p class="text-sm text-gray-900 dark:text-gray-100">{{ $formationUser->formation->description }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'inscription</h3>
            <p class="text-sm text-gray-900 dark:text-gray-100">
              {{ $formationUser->enrolled_at ? $formationUser->enrolled_at->format('d/m/Y') : 'N/A' }}
            </p>
          </div>

          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de completion</h3>
            <p class="text-sm text-gray-900 dark:text-gray-100">
              {{ $formationUser->completed_at ? $formationUser->completed_at->format('d/m/Y') : 'N/A' }}
            </p>
          </div>

          <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Progression</h3>
            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $formationUser->progress_percent ?? 0 }}%</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <!-- Student Signature -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Signature de l'étudiant</h2>

        @if($studentSignature)
        <div class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-900/50">
          <img src="data:image/png;base64,{{ $studentSignature->signature_data }}"
               alt="Signature de l'étudiant"
               class="max-w-full h-auto border border-gray-200 dark:border-gray-600 rounded">
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            Signé le {{ $studentSignature->signed_at->format('d/m/Y à H:i') }}
          </p>
        </div>
        @else
        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
          </svg>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Aucune signature enregistrée</p>
        </div>
        @endif
      </div>

      <!-- Trainer Signature -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Signature du formateur</h2>

        @if($formationUser->trainerSignature)
        <div class="border-2 border-green-300 dark:border-green-600 rounded-lg p-4 bg-green-50 dark:bg-green-900/10">
          <img src="data:image/png;base64,{{ $formationUser->trainerSignature->signature_data }}"
               alt="Signature du formateur"
               class="max-w-full h-auto border border-green-200 dark:border-green-700 rounded">
          <p class="text-xs text-green-600 dark:text-green-400 mt-2">
            Validé par {{ $formationUser->completionValidatedBy->name ?? 'Formateur' }} le {{ $formationUser->completion_validated_at->format('d/m/Y à H:i') }}
          </p>
        </div>
        @else
        <div class="border-2 border-dashed border-amber-300 dark:border-amber-600 rounded-lg p-8 text-center bg-amber-50 dark:bg-amber-900/10">
          <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-sm text-amber-700 dark:text-amber-300 mt-2 font-medium">En attente de signature</p>
        </div>
        @endif
      </div>
    </div>

    <!-- Actions -->
    @if($formationUser->completion_request_status === 'pending')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>

      <div class="space-y-6">
        <!-- Approve Form -->
        <div>
          <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Approuver la demande</h3>
          <form method="POST" action="{{ route('superadmin.completion-requests.approve', $formationUser) }}" id="approve-form">
            @csrf
            <div class="space-y-4">
              <div>
                <label for="trainer_signature" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Votre signature
                </label>
                <div class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-900">
                  <canvas id="signature-canvas"
                          class="border border-gray-200 dark:border-gray-600 rounded w-full"
                          width="400"
                          height="200"
                          style="cursor: crosshair;"></canvas>
                  <input type="hidden" name="trainer_signature" id="trainer_signature">
                  <div class="mt-2 flex justify-between">
                    <button type="button" id="clear-signature"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                      Effacer
                    </button>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Signez dans le cadre ci-dessus</span>
                  </div>
                </div>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                  <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Approuver
                </button>
              </div>
            </div>
          </form>
        </div>

        <hr class="border-gray-200 dark:border-gray-600">

        <!-- Reject Form -->
        <div>
          <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Rejeter la demande</h3>
          <form method="POST" action="{{ route('superadmin.completion-requests.reject', $formationUser) }}">
            @csrf
            <div class="space-y-4">
              <div>
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Raison du rejet
                </label>
                <textarea name="rejection_reason"
                          id="rejection_reason"
                          rows="3"
                          class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                          placeholder="Expliquez pourquoi cette demande est rejetée..."
                          required></textarea>
              </div>

              <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                  <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Rejeter
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <div class="text-center">
        @if($formationUser->completion_request_status === 'approved')
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Cette demande a été approuvée
          </div>
        @else
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded-lg">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 00.707.293 1 1 0 00.707-1.707L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            Cette demande a été rejetée
          </div>
        @endif
      </div>
    </div>
    @endif
  </div>

  @if($formationUser->completion_request_status === 'pending')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const canvas = document.getElementById('signature-canvas');
      const signatureInput = document.getElementById('trainer_signature');
      const clearButton = document.getElementById('clear-signature');
      const form = document.getElementById('approve-form');

      let isDrawing = false;
      const ctx = canvas.getContext('2d');

      // Set canvas size
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;

      // Set drawing properties
      ctx.strokeStyle = '#000';
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.lineJoin = 'round';

      // Drawing functions
      function startDrawing(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        ctx.beginPath();
        ctx.moveTo(x, y);
      }

      function draw(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        ctx.lineTo(x, y);
        ctx.stroke();
      }

      function stopDrawing() {
        isDrawing = false;
        updateSignatureInput();
      }

      function updateSignatureInput() {
        const dataURL = canvas.toDataURL('image/png');
        const base64 = dataURL.split(',')[1];
        signatureInput.value = base64;
      }

      // Event listeners
      canvas.addEventListener('mousedown', startDrawing);
      canvas.addEventListener('mousemove', draw);
      canvas.addEventListener('mouseup', stopDrawing);
      canvas.addEventListener('mouseout', stopDrawing);

      // Touch events for mobile
      canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousedown', {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      });

      canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent('mousemove', {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      });

      canvas.addEventListener('touchend', function(e) {
        e.preventDefault();
        const mouseEvent = new MouseEvent('mouseup');
        canvas.dispatchEvent(mouseEvent);
      });

      // Clear button
      clearButton.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
      });

      // Form validation
      form.addEventListener('submit', function(e) {
        if (!signatureInput.value) {
          e.preventDefault();
          alert('Veuillez signer avant d\'approuver la demande.');
          return false;
        }
      });
    });
  </script>
  @endif
</x-superadmin-layout>
