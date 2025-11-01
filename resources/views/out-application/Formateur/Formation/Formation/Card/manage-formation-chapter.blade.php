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
