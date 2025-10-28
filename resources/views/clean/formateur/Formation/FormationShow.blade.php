<x-app-layout>
  <!-- Formation Dashboard -->
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Formation Header Card -->
      <div
        class="bg-gradient-to-br from-indigo-50 to-blue-50 overflow-hidden shadow-sm sm:rounded-xl border border-indigo-100 mb-8">
        <div class="p-8">
          <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
              <a href="{{route('formateur.home')}}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors duration-200 mr-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux formations
              </a>
              <h1 class="text-3xl font-bold text-gray-900 mb-3 flex items-center">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                  </path>
                </svg>
                {{ $formation->title }}
              </h1>
              <p class="text-gray-700 text-lg leading-relaxed">
                {{ $formation->description }}
              </p>
              <div class="mt-6">
                <div class="overflow-hidden rounded-3xl border border-white/60 shadow-inner">
                  <img
                    src="{{ $formation->cover_image_url }}"
                    alt="Image de couverture de {{ $formation->title }}"
                    class="h-56 w-full object-cover sm:h-64 lg:h-72"
                    onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>




      <!-- Navigation Cards -->
      <div class="mb-8 p-5">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Gestion de la formation</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Edit Formation Card -->
          <a href="{{ route('formateur.formation.edit', $formation) }}"
            class="group relative bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg group-hover:bg-indigo-200 transition-colors duration-200">
                  <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                  </svg>
                </div>
                <div class="ml-4">
                  <h3
                    class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                    Modifier la formation
                  </h3>
                  <p class="text-sm text-gray-600">
                    Titre, description et paramètres
                  </p>
                </div>
              </div>
              <div class="flex items-center text-sm text-indigo-600 font-medium">
                Modifier
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </a>

          <!-- Pricing Card -->
          <a href="{{ route('formateur.formation.pricing.edit', $formation) }}"
            class="group relative bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-lg group-hover:bg-emerald-200 transition-colors duration-200">
                  <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                  </svg>
                </div>
                <div class="ml-4">
                  <h3
                    class="text-lg font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors duration-200">
                    Configurer la tarification
                  </h3>
                  <p class="text-sm text-gray-600">
                    Prix et options de paiement
                  </p>
                </div>
              </div>
              <div class="flex items-center text-sm text-emerald-600 font-medium">
                Configurer
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </a>

          <!-- Chapters Card -->
          <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
            class="group relative bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors duration-200">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                </div>
                <div class="ml-4">
                  <h3
                    class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                    Gérer les chapitres
                  </h3>
                  <p class="text-sm text-gray-600">
                    {{ $formation->chapters->count() }} chapitre{{ $formation->chapters->count() > 1 ? 's' : '' }}
                  </p>
                </div>
              </div>
              <div class="flex items-center text-sm text-blue-600 font-medium">
                Gérer
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </a>
        </div>
      </div>

      <!-- Completion Documents Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-10">
        <div class="p-6 md:p-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Documents de fin de formation</h2>
              <p class="text-sm text-gray-600 mt-1">
                Ajoutez les documents remis aux apprenants une fois la formation terminee.
              </p>
            </div>
          </div>

          <form action="{{ route('formateur.formation.completion-documents.store', $formation) }}" method="POST"
            enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
              <label for="document_title" class="block text-sm font-medium text-gray-700 mb-2">Titre du document</label>
              <input type="text" id="document_title" name="title" value="{{ old('title') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Ex. Attestation de formation" required>
              @error('title')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">Fichier</label>
              <input type="file" id="document_file" name="file"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100"
                required>
              @error('file')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex justify-end">
              <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition-colors duration-200">
                Ajouter le document
              </button>
            </div>
          </form>

          <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents disponibles</h3>
            @if($formation->completionDocuments->isNotEmpty())
            <ul class="divide-y divide-gray-200">
              @foreach($formation->completionDocuments as $document)
              <li class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ $document->title }}</p>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ $document->original_name }} @if($document->size) - {{ number_format($document->size / 1024, 1) }}
                    Ko @endif
                  </p>
                  @if($document->created_at)
                  <p class="text-xs text-gray-500 mt-1">
                    Ajoute le {{ $document->created_at->format('d/m/Y H:i') }}
                  </p>
                  @endif
                </div>
                <div class="mt-3 sm:mt-0 flex items-center gap-3">
                  <a href="{{ Storage::disk('public')->url($document->file_path) }}" target="_blank"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                    Telecharger
                  </a>
                  <form
                    action="{{ route('formateur.formation.completion-documents.destroy', [$formation, $document]) }}"
                    method="POST" onsubmit="return confirm('Supprimer ce document ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-200">
                      Supprimer
                    </button>
                  </form>
                </div>
              </li>
              @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500">Aucun document de fin de formation pour le moment.</p>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Edit Content Modal -->
  <div id="editContentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-xl bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-900">
            Modifier le contenu
          </h3>
          <button type="button" onclick="closeEditContentModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form action="{{
                        route('formateur.formation.update', $formation)
                    }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-4">
            <label for="content_title" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
            <input type="text" id="content_title" name="title" value="{{ old('title', $formation->title) }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Entrez le titre de la formation" />
            @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-6">
            <label for="content_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea id="content_description" name="description" rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Entrez la description de la formation">{{ old('description', $formation->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeEditContentModal()"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
              Annuler
            </button>
            <button type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Price Modal -->
  <div id="editPriceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-xl bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-900">
            Modifier le prix
          </h3>
          <button type="button" onclick="closeEditPriceModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form action="{{
                        route('formateur.formation.update', $formation)
                    }}" method="POST">
          @csrf @method('PUT')

          <div class="mb-6">
            <label for="price_amount" class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
            <input type="number" id="price_amount" name="money_amount" min="0" step="0.01"
              value="{{ old('money_amount', $formation->money_amount ?? 0) }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="0.00" />
            @error('money_amount')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeEditPriceModal()"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
              Annuler
            </button>
            <button type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors duration-200">
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Lesson Title Modal -->
  <div id="editLessonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 sm:w-96 shadow-lg rounded-xl bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-900">
            Modifier le nom de la leçon
          </h3>
          <button type="button" onclick="closeEditLessonModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form id="editLessonForm" method="POST">
          @csrf @method('PUT')

          <div class="mb-4">
            <label for="lesson_title" class="block text-sm font-medium text-gray-700 mb-2">Nouveau nom de la
              leçon</label>
            <input type="text" id="lesson_title" name="lesson_title"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Entrez le nouveau nom de la leçon" />
            @error('lesson_title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeEditLessonModal()"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
              Annuler
            </button>
            <button type="submit"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors duration-200">
              Enregistrer
            </button>
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
    // Global variables to store current lesson data
    let currentLessonId = null;
    let currentFormationId = null;
    let currentChapterId = null;



    function openEditContentModal() {
      document.getElementById("editContentModal").classList.remove("hidden");
      document.body.classList.add("overflow-hidden");
    }

    function closeEditContentModal() {
      document.getElementById("editContentModal").classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }

    function openEditPriceModal() {
      document.getElementById("editPriceModal").classList.remove("hidden");
      document.body.classList.add("overflow-hidden");
    }

    function closeEditPriceModal() {
      document.getElementById("editPriceModal").classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    }

    function openEditLessonModal(
      lessonId,
      currentTitle,
      formationId,
      chapterId
    ) {
      currentLessonId = lessonId;
      currentFormationId = formationId;
      currentChapterId = chapterId;

      // Set the current title in the input field
      document.getElementById("lesson_title").value = currentTitle;

      // Update form action
      const form = document.getElementById("editLessonForm");
      form.action = `/formateur/formation/${formationId}/chapitre/${chapterId}/lesson/${lessonId}/title`;

      // Show modal
      document
        .getElementById("editLessonModal")
        .classList.remove("hidden");
      document.body.classList.add("overflow-hidden");

      // Focus on input field
      document.getElementById("lesson_title").focus();
    }

    function closeEditLessonModal() {
      document.getElementById("editLessonModal").classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
      currentLessonId = null;
      currentFormationId = null;
      currentChapterId = null;
    }

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
    fetch('/formateur/formation/' + formationId + '/toggle-status', {
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



    document
      .getElementById("editContentModal")
      .addEventListener("click", function (e) {
        if (e.target === this) {
          closeEditContentModal();
        }
      });

    document
      .getElementById("editPriceModal")
      .addEventListener("click", function (e) {
        if (e.target === this) {
          closeEditPriceModal();
        }
      });

    document
      .getElementById("editLessonModal")
      .addEventListener("click", function (e) {
        if (e.target === this) {
          closeEditLessonModal();
        }
      });

    // Handle lesson form submission with AJAX
    document
      .getElementById("editLessonForm")
      .addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector(
          'button[type="submit"]'
        );
        const originalText = submitButton.textContent;

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
              // Update the lesson title in the DOM
              const titleElement = document.getElementById(
                `lesson-title-${currentLessonId}`
              );
              if (titleElement) {
                titleElement.textContent = data.new_title;
              }

              // Close modal and show success message
              closeEditLessonModal();

              // Show success message
              showSuccessMessage(data.message);
            } else {
              throw new Error(
                data.message || "Erreur lors de la modification"
              );
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            // Show error message in the modal
            const errorDiv = document.createElement("div");
            errorDiv.className = "mt-2 text-sm text-red-600";
            errorDiv.textContent = error.message;

            const existingError =
              this.querySelector(".text-red-600");
            if (existingError) {
              existingError.remove();
            }

            const titleInput =
              document.getElementById("lesson_title");
            titleInput.parentNode.appendChild(errorDiv);
          })
          .finally(() => {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
          });
      });

    function showSuccessMessage(message) {
      // Remove existing success message
      const existingMessage = document.getElementById("successMessage");
      if (existingMessage) {
        existingMessage.remove();
      }

      // Create new success message
      const successDiv = document.createElement("div");
      successDiv.id = "successMessage";
      successDiv.className =
        "fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50";
      successDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ` + message + `
                </div>
            `;

      document.body.appendChild(successDiv);

      // Auto-hide after 5 seconds
      setTimeout(() => {
        successDiv.style.opacity = "0";
        setTimeout(() => {
          successDiv.remove();
        }, 300);
      }, 5000);
    }

    // Auto-hide success message after 5 seconds (for page load messages)
    const successMessage = document.getElementById("successMessage");
    if (successMessage) {
      setTimeout(() => {
        successMessage.style.opacity = "0";
        setTimeout(() => {
          successMessage.remove();
        }, 300);
      }, 5000);
    }

    // Keyboard shortcuts
    document.addEventListener("keydown", function (e) {
      // Escape key closes modals
      if (e.key === "Escape") {
        closeEditContentModal();
        closeEditPriceModal();
        closeEditLessonModal();
      }
    });
  </script>
</x-app-layout>
