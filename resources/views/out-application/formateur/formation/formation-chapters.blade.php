<x-app-layout>
  <!-- Chapter Management Page -->
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Page Header -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-4 mb-3">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Retour
                </a>
              </div>
              <h1 class="text-2xl font-bold text-gray-900 mb-2">
                Gestion des chapitres
              </h1>
              <p class="text-gray-600 text-base">
                {{ $formation->title }}
              </p>
              <p class="text-gray-500 text-sm mt-1">
                {{ $formation->description }}
              </p>
            </div>
            <div class="flex-shrink-0">
              <form method="post" action="{{ route('formateur.formation.chapter.store', ['formation' => $formation]) }}" class="inline">
                @csrf
                <button class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Nouveau chapitre
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>


      @if($formation->chapters->isEmpty())
      <!-- Empty State -->
      <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
        <h3 class="text-xl font-medium text-gray-900 mb-2">
          Aucun chapitre trouvé
        </h3>
        <p class="text-gray-500 mb-8 max-w-sm mx-auto">
          Commencez par créer votre premier chapitre pour structurer votre formation.
        </p>
        <form action="{{ route('formateur.formation.chapter.store', ['formation' => $formation]) }}" method="POST" class="inline">
          @csrf
          <button
            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
              </path>
            </svg>
            Créer le premier chapitre
          </button>
        </form>
      </div>
      @else
      <!-- Chapters Grid -->
      <div class="grid gap-6">
        @foreach($formation->chapters as $chapter)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
          <!-- Chapter Header -->
          <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-xl shadow-sm">
                  <span class="text-white font-bold text-lg">{{ $chapter->position }}</span>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-900 mb-1">
                    {{ $chapter->title }}
                  </h3>
                  <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ $chapter->lessons->count() }} leçon{{ $chapter->lessons->count() > 1 ? 's' : '' }}
                  </p>
                </div>
              </div>
              <div class="flex items-center space-x-3">
                <a href="{{ route('formateur.formation.chapter.edit', ['formation' => $formation, 'chapter' => $chapter]) }}"
                  class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200">
                  <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                  </svg>
                  Modifier
                </a>
                <form method="post" action="{{ route('formateur.formation.chapter.lesson.add', ['formation' => $formation, 'chapter' => $chapter]) }}" class="inline">
                  @csrf
                  <button class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouvelle leçon
                  </button>
                </form>
              </div>
            </div>
          </div>

          @if($chapter->lessons->isEmpty())
          <!-- Empty Lessons State -->
          <div class="px-6 py-12 text-center border-t border-gray-100">
            <div class="max-w-sm mx-auto">
              <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
              <h4 class="text-sm font-medium text-gray-900 mb-2">Chapitre vide</h4>
              <p class="text-gray-500 text-sm mb-6">
                Ajoutez votre première leçon pour commencer à construire ce chapitre.
              </p>
              <form method="post" action="{{ route('formateur.formation.chapter.lesson.add', ['formation' => $formation, 'chapter' => $chapter]) }}" class="inline">
                @csrf
                <button class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Ajouter une leçon
                </button>
              </form>
            </div>
          </div>
          @else
          <!-- Lessons List -->
          <div class="divide-y divide-gray-100">
            @foreach($chapter->lessons as $lesson)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 flex-1 min-w-0">
                  <!-- Lesson Position Badge -->
                  <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-lg flex-shrink-0">
                    <span class="text-sm font-semibold text-gray-600">{{ $lesson->position }}</span>
                  </div>

                  <!-- Lesson Title and Edit -->
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2">
                      <h4 class="text-sm font-medium text-gray-900 truncate" id="lesson-title-{{ $lesson->id }}">
                        {{ $lesson->getName() }}
                      </h4>
                      <button type="button"
                        onclick="openEditLessonModal({{ $lesson->id }}, '{{ addslashes($lesson->getName()) }}', {{ $formation->id }}, {{ $chapter->id }})"
                        class="inline-flex items-center p-1 text-gray-400 hover:text-indigo-600 rounded transition-colors duration-200 opacity-0 group-hover:opacity-100"
                        title="Modifier le nom de la leçon">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                          </path>
                        </svg>
                      </button>
                    </div>
                  </div>

                  <!-- Lesson Type Badge -->
                  <div class="flex-shrink-0">
                    @if($lesson->lessonable)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{
                                                        $lesson->lessonable_type === 'App\Models\Quiz' ? 'bg-indigo-100 text-indigo-800' :
                                                        ($lesson->lessonable_type === 'App\Models\VideoContent' ? 'bg-emerald-100 text-emerald-800' :
                                                        ($lesson->lessonable_type === 'App\Models\TextContent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'))
                                                    }}">
                      @if($lesson->lessonable_type === 'App\Models\Quiz')
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Quiz
                      @elseif($lesson->lessonable_type === 'App\Models\VideoContent')
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                      </svg>
                      Vidéo
                      @elseif($lesson->lessonable_type === 'App\Models\TextContent')
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      Texte
                      @else
                      {{ class_basename($lesson->lessonable_type) }}
                      @endif
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800">
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                      </svg>
                      Non défini
                    </span>
                    @endif
                  </div>
                </div>

                <!-- Lesson Actions -->
                <div class="flex items-center space-x-1 ml-4 flex-shrink-0">
                  @if($lesson->lessonable)
                  @if($lesson->lessonable_type === 'App\Models\Quiz')
                  <a href="{{ route('formateur.formation.chapter.lesson.quiz.edit', ['formation' => $formation, 'chapter' => $chapter, 'lesson' => $lesson]) }}"
                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:text-indigo-900 hover:bg-indigo-50 rounded-md transition-colors duration-200">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                  </a>
                  @elseif($lesson->lessonable_type === 'App\Models\VideoContent')
                  <a href="{{ route('formateur.formation.chapter.lesson.video.edit', ['formation' => $formation, 'chapter' => $chapter, 'lesson' => $lesson]) }}"
                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50 rounded-md transition-colors duration-200">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Modifier
                  </a>
                  @elseif($lesson->lessonable_type === 'App\Models\TextContent')
                  <a href="{{ route('formateur.formation.chapter.lesson.text.edit', ['formation' => $formation, 'chapter' => $chapter, 'lesson' => $lesson]) }}"
                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                  </a>
                  @endif
                  @else
                  <a href="{{ route('formateur.formation.chapter.lesson.define', ['formation' => $formation, 'chapter' => $chapter, 'lesson' => $lesson]) }}"
                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:text-indigo-900 hover:bg-indigo-50 rounded-md transition-colors duration-200"
                    title="Choisir le type de leçon (Quiz, Vidéo ou Texte)">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                    Définir
                  </a>
                  @endif

                  <!-- Delete Lesson Button -->
                  <form method="POST" action="{{ route('formateur.formation.chapter.lesson.delete', ['formation' => $formation, 'chapter' => $chapter, 'lesson' => $lesson]) }}" class="inline"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon? Cette action est irréversible.')">
                    @csrf
                    @method('delete')
                    
                    <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-700 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors duration-200">
                      <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                      Supprimer
                    </button>
                  </form>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
        @endforeach
      </div>
      @endif
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

      function openEditLessonModal(lessonId, currentTitle, formationId, chapterId) {
        currentLessonId = lessonId;
        currentFormationId = formationId;
        currentChapterId = chapterId;

        // Set the current title in the input field
        document.getElementById("lesson_title").value = currentTitle;

        // Update form action
        const form = document.getElementById("editLessonForm");
        form.action = `/formateur/formation/${formationId}/chapitre/${chapterId}/lesson/${lessonId}/title`;

        // Show modal
        document.getElementById("editLessonModal").classList.remove("hidden");
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

      // Handle lesson form submission with AJAX
      document.getElementById("editLessonForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
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
              const titleElement = document.getElementById(`lesson-title-${currentLessonId}`);
              if (titleElement) {
                titleElement.textContent = data.new_title;
              }

              // Close modal and show success message
              closeEditLessonModal();

              // Show success message
              showSuccessMessage(data.message);
            } else {
              throw new Error(data.message || "Erreur lors de la modification");
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            // Show error message in the modal
            const errorDiv = document.createElement("div");
            errorDiv.className = "mt-2 text-sm text-red-600";
            errorDiv.textContent = error.message;

            const existingError = this.querySelector(".text-red-600");
            if (existingError) {
              existingError.remove();
            }

            const titleInput = document.getElementById("lesson_title");
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
                    ${message}
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
          closeEditLessonModal();
        }
      });

      // Close modal when clicking outside
      document.getElementById("editLessonModal").addEventListener("click", function (e) {
        if (e.target === this) {
          closeEditLessonModal();
        }
      });
    </script>
</x-app-layout>
