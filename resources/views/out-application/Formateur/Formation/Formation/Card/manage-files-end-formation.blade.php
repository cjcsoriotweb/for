<!-- Completion Documents Card -->
          <a href="{{ route('formateur.formation.completion-documents.index', $formation) }}"
            class="group relative bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors duration-200">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z">
                    </path>
                  </svg>
                </div>
                <div class="ml-4">
                  <h3
                    class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">
                    Documents de fin de formation
                  </h3>
                  <p class="text-sm text-gray-600">
                    Gérez les attestations et supports remis aux apprenants.
                  </p>
                </div>
              </div>
              <div class="flex items-center text-sm text-purple-600 font-medium">
                Ouvrir l'espace documents
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </a>