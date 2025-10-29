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
                    onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
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







          <!-- Modifier la formation (include) !-->
          @include('clean.formateur.Formation.Formation.Card.modify-formation-informations')
          @include('clean.formateur.Formation.Formation.Card.modify-formation-ia')
          @include('clean.formateur.Formation.Formation.Card.modify-formation-pricing')
          @include('clean.formateur.Formation.Formation.Card.manage-formation-chapter')
          @include('clean.formateur.Formation.Formation.Card.manage-files-end-formation')
          @include('clean.formateur.Formation.Formation.Card.owner-formation-modify')

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
      const formationId = {
        {
          $formation - > id
        }
      };
      const currentStatus = {
        {
          $formation - > active ? 'true' : 'false'
        }
      };

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
      .addEventListener("click", function(e) {
        if (e.target === this) {
          closeEditContentModal();
        }
      });

    document
      .getElementById("editPriceModal")
      .addEventListener("click", function(e) {
        if (e.target === this) {
          closeEditPriceModal();
        }
      });

    document
      .getElementById("editLessonModal")
      .addEventListener("click", function(e) {
        if (e.target === this) {
          closeEditLessonModal();
        }
      });

    // Handle lesson form submission with AJAX
    document
      .getElementById("editLessonForm")
      .addEventListener("submit", function(e) {
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
    document.addEventListener("keydown", function(e) {
      // Escape key closes modals
      if (e.key === "Escape") {
        closeEditContentModal();
        closeEditPriceModal();
        closeEditLessonModal();
      }
    });
  </script>
</x-app-layout>