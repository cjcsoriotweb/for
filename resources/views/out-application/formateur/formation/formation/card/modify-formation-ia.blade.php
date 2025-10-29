          <!-- AI Trainer Card -->
          <a href="{{ route('formateur.formation.ai.edit', $formation) }}"
            class="group relative bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">
            <div class="p-6">
              <div class="flex items-center mb-4">
                <div
                  class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors duration-200">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0v7m0 0l-3.5-2m3.5 2l3.5-2">
                    </path>
                  </svg>
                </div>
                <div class="ml-4">
                  <h3
                    class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">
                    Configurer le formateur IA
                  </h3>
                  <p class="text-sm text-gray-600">
                    Associez ou retirez un assistant IA pour cette formation.
                  </p>
                </div>
              </div>
              <div class="flex items-center text-sm text-purple-600 font-medium">
                Gérer
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </div>
            </div>
          </a>