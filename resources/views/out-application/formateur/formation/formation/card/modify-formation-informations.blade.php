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
                    Choisissez la section à mettre à jour : titre, description ou visuel.
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
