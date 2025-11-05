<x-admin.global-layout
    icon="check_circle"
    :title="__('Demande de validation')"
    :subtitle="__('Examiner et traiter une demande de validation de formation')"
>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

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

    <!-- Documents joints -->
    @if($formationUser->completion_documents && count($formationUser->completion_documents) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Documents joints</h2>

      <div class="space-y-3">
        @foreach($formationUser->completion_documents as $index => $document)
        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-600">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
              @if(str_contains($document['mime_type'], 'pdf'))
                <svg class="h-8 w-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
              @elseif(str_contains($document['mime_type'], 'word') || str_contains($document['mime_type'], 'document'))
                <svg class="h-8 w-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
              @elseif(str_contains($document['mime_type'], 'excel') || str_contains($document['mime_type'], 'spreadsheet'))
                <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                </svg>
              @else
                <svg class="h-8 w-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              @endif
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ $document['original_name'] }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ number_format($document['size'] / 1024, 1) }} KB • {{ strtoupper(pathinfo($document['original_name'], PATHINFO_EXTENSION)) }}
              </p>
            </div>
          </div>
          <a href="{{ route('superadmin.completion-requests.documents.download', [$formationUser, $index]) }}"
             class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            Télécharger
          </a>
        </div>
        @endforeach
      </div>
    </div>
    @endif



    <!-- Actions -->
    @if($formationUser->completion_request_status === 'pending')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>

      <div class="space-y-6">
        <!-- Approve Form -->
        <div>
          <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">Approuver la demande</h3>
          <form method="POST" action="{{ route('superadmin.completion-requests.approve', $formationUser) }}" enctype="multipart/form-data" id="approve-form">
            @csrf
            <div class="space-y-4">
              <div>
                <label for="completion_documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Documents joints (optionnel)
                  <span class="text-xs text-gray-500 dark:text-gray-400 block">Factures, certificats, documents administratifs...</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-900/50">
                  <input type="file"
                         name="completion_documents[]"
                         id="completion_documents"
                         multiple
                         accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                         class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Formats acceptés : PDF, Word, Excel, Images. Taille max : 10MB par fichier.
                  </p>
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
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-lg mb-4">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Cette demande a été approuvée
          </div>

          <!-- Cancel Form -->
          <div class="mt-6">
            <form method="POST" action="{{ route('superadmin.completion-requests.cancel', $formationUser) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette validation ? La demande reviendra en statut \"en attente\".')">
              @csrf
              <button type="submit"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Annuler la validation
              </button>
            </form>
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


</x-admin.global-layout>
