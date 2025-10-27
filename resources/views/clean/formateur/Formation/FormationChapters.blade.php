<x-app-layout>
  <!-- Chapter Management Page -->
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
                  Retour à la formation
                </a>
                <div class="h-6 w-px bg-gray-300 mx-4"></div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                  <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                  Gestion des chapitres - {{ $formation->title }}
                </h1>
              </div>
              <p class="text-gray-700 text-lg leading-relaxed">
                {{ $formation->description }}
              </p>
            </div>

            <!-- Sidebar avec composants modulaires -->
            <x-formateur.formation.formation-sidebar :formation="$formation" />

            <!-- Action Buttons -->
            <div class="flex flex-col space-y-3">
              <a href="{{ route('formateur.formation.edit', $formation) }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                  </path>
                </svg>
                Modifier la formation
              </a>

              <form action="{{ route('formateur.formation.chapter.add.post', $formation) }}" method="POST"
                class="inline">
                @csrf
                <button
                  class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Créer un chapitre
                </button>
              </form>

              <a href="{{ route('formateur.formation.show', $formation) }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                  </path>
                </svg>
                Voir la formation
              </a>
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
        <form action="{{ route('formateur.formation.chapter.add.post', $formation) }}" method="POST" class="inline">
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
        <div
          class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
          <!-- Chapter Header -->
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-full">
                  <span class="text-indigo-600 font-bold">{{ $chapter->position }}</span>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-900 mb-1">
                    {{ $chapter->title }}
                  </h3>
                  <p class="text-sm text-gray-600">
                    {{ $chapter->lessons->count() }} leçon{{ $chapter->lessons->count() > 1 ? 's' : '' }}
                  </p>
                </div>
              </div>
              <div class="flex items-center space-x-3">
                <a href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                  </svg>
                  Modifier
                </a>
                <form method="post"
                  action="{{ route('formateur.formation.chapter.lesson.add.post', [$formation, $chapter]) }}"
                  class="inline">
                  @csrf
                  <button
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une leçon
                  </button>
                </form>
                <form method="POST"
                  action="{{ route('formateur.formation.chapter.delete.post', [$formation, $chapter]) }}" class="inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chapitre et toutes ses leçons? Cette action est irréversible.')">
                  @csrf
                  <button type="submit"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                      </path>
                    </svg>
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
          </div>

          @if($chapter->lessons->isEmpty())
          <!-- Empty Lessons State -->
          <div class="px-6 py-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
              </path>
            </svg>
            <p class="text-gray-500 text-sm mb-4">
              Aucune leçon dans ce chapitre.
            </p>
            <form method="post"
              action="{{ route('formateur.formation.chapter.lesson.add.post', [$formation, $chapter]) }}"
              class="inline">
              @csrf
              <button
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                  </path>
                </svg>
                Ajouter la première leçon
              </button>
            </form>
          </div>
          @else
          <!-- Lessons List -->
          <div class="p-6">
            <div class="space-y-3">
              @foreach($chapter->lessons as $lesson)
              <div
                class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4 flex-1">
                    <!-- Lesson Position Badge -->
                    <div class="flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-full">
                      <span class="text-xs font-bold text-gray-600">{{ $lesson->position }}</span>
                    </div>

                    <!-- Lesson Title -->
                    <div class="flex-1">
                      <div class="flex items-center space-x-2">
                        <h4 class="text-sm font-semibold text-gray-900 mb-1" id="lesson-title-{{ $lesson->id }}">
                          {{ $lesson->title }}
                        </h4>
                        <button type="button"
                          onclick="openEditLessonModal({{ $lesson->id }}, '{{ addslashes($lesson->title) }}', {{ $formation->id }}, {{ $chapter->id }})"
                          class="inline-flex items-center p-1 text-gray-400 hover:text-indigo-600 rounded-md hover:bg-indigo-50 transition-colors duration-200"
                          title="Modifier le nom de la leçon">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                          </svg>
                        </button>
                      </div>
                    </div>

                    <!-- Lesson Type Badge -->
                    <div class="flex items-center space-x-2">
                      @if($lesson->lessonable)
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{
                                                                  $lesson->lessonable_type === 'App\Models\Quiz' ? 'bg-blue-100 text-blue-800' :
                                                                  ($lesson->lessonable_type === 'App\Models\VideoContent' ? 'bg-green-100 text-green-800' :
                                                                  ($lesson->lessonable_type === 'App\Models\TextContent' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))
                                                              }}">
                        @if($lesson->lessonable_type === 'App\Models\Quiz')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd"></path>
                        </svg>
                        Quiz
                        @elseif($lesson->lessonable_type === 'App\Models\VideoContent')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path
                            d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
                          </path>
                        </svg>
                        Vidéo
                        @elseif($lesson->lessonable_type === 'App\Models\TextContent')
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 5a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd"></path>
                        </svg>
                        Texte @else
                        {{ class_basename($lesson->lessonable_type) }}
                        @endif
                      </span>
                      @else
                      <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                        </svg>
                        Non défini
                      </span>
                      @endif
                    </div>
                  </div>

                  <!-- Lesson Actions -->
                  <div class="flex items-center space-x-2 ml-4">
                    @if($lesson->lessonable)
                    @if($lesson->lessonable_type === 'App\Models\Quiz')
                    <a href="{{ route('formateur.formation.chapter.lesson.quiz.edit', [$formation, $chapter, $lesson]) }}"
                      class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                      </svg>
                      Modifier
                    </a>
                    @elseif($lesson->lessonable_type === 'App\Models\VideoContent')
                    <a href="{{ route('formateur.formation.chapter.lesson.video.edit', [$formation, $chapter, $lesson]) }}"
                      class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                      </svg>
                      Modifier
                    </a>
                    @elseif($lesson->lessonable_type === 'App\Models\TextContent')
                    <a href="{{ route('formateur.formation.chapter.lesson.text.edit', [$formation, $chapter, $lesson]) }}"
                      class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 hover:bg-blue-50 rounded-md transition-colors duration-200">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                      </svg>
                      Modifier
                    </a>
                    @endif @else
                    <a href="{{ route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson]) }}"
                      class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50 rounded-md transition-colors duration-200"
                      title="Choisir le type de leçon (Quiz, Vidéo ou Texte)">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      Définir le type
                    </a>
                    @endif

                    <!-- Delete Lesson Button -->
                    <form method="POST"
                      action="{{ route('formateur.formation.chapter.lesson.delete.post', [$formation, $chapter, $lesson]) }}"
                      class="inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon? Cette action est irréversible.')">
                      @csrf
                      <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 hover:text-red-900 hover:bg-red-50 rounded-md transition-colors duration-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                          </path>
                        </svg>
                        Supprimer
                      </button>
                    </form>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
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