@props(['formation', 'team' => null])

<div
  class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
  <div class="p-8 text-gray-900 dark:text-gray-100">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center space-x-3">
        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
            </path>
          </svg>
        </div>
        <h2
          class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
          Chapitres de formation
        </h2>
      </div>
      <div class="text-sm text-gray-500 dark:text-gray-400 font-medium">
        {{ $formation->chapters->count() }}
        chapitre{{ $formation->chapters->count() > 1 ? 's' : '' }}
      </div>
    </div>

    @if($formation->chapters && $formation->chapters->count() > 0)
    <!-- Continue Button Section -->
    @php $studentFormationService =
    app(\App\Services\Formation\StudentFormationService::class);
    $currentLesson = $studentFormationService->getCurrentLesson($formation,
    auth()->user()); @endphp @if($currentLesson)

    <div class="mb-5">
      @livewire('eleve.formation.autoplay', [
          'formation' => $formation,
          'currentLesson' => $currentLesson,
          'team' => $team ?? auth()->user()?->currentTeam,
      ])
    </div>

    @endif

    <div class="space-y-6">
      @foreach($formation->chapters as $index => $chapter)
      <div class="group relative">
        <!-- Chapter Card -->
        <div
          class="relative bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 overflow-hidden">
          <!-- Progress Bar -->
          <div
            class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          </div>

          <div class="p-6">
            <!-- Chapter Header -->
            <div class="flex items-start justify-between mb-4">
              <div class="flex items-center space-x-4">
                <div
                  class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full text-white font-bold text-lg shadow-lg">
                  {{ $index + 1 }}
                </div>
                <div>
                  <h3
                    class="text-xl font-bold {{ $chapter->is_accessible ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }} mb-1">
                    {{ $chapter->title }}
                    @if(!$chapter->is_accessible)
                    <svg class="inline w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                      </path>
                    </svg>
                    @endif
                  </h3>
                  <div class="flex items-center space-x-2">
                    @if($chapter->is_completed)
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-green-500 to-green-600 text-white shadow-sm">
                      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clip-rule="evenodd"></path>
                      </svg>
                      Terminé
                    </span>
                    @elseif($chapter->is_current)
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm">
                      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                          clip-rule="evenodd"></path>
                      </svg>
                      En cours
                    </span>
                    @else
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-sm">
                      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                          clip-rule="evenodd"></path>
                      </svg>
                      Verrouillé
                    </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <!-- Chapter Description -->
            @if($chapter->description)
            <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
              {{ $chapter->description }}
            </p>
            @endif

            <!-- Lessons Section -->
            @if($chapter->lessons && $chapter->lessons->count() > 0)
            <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
              <div class="flex items-center mb-4">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
                </svg>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                  Leçons ({{ $chapter->lessons->count() }})
                </h4>
              </div>

              <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($chapter->lessons as $lesson) @php
                $lessonType = $lesson->lessonable_type ===
                \App\Models\Quiz::class ? 'Quiz' :
                ($lesson->lessonable_type ===
                \App\Models\VideoContent::class ? 'Vidéo' :
                'Contenu'); $typeColors = ['Quiz' => 'blue',
                'Vidéo' => 'purple', 'Contenu' => 'green'];
                $color = $typeColors[$lessonType] ?? 'gray';
                $cardClasses = 'group/lesson relative rounded-lg
                p-4 transition-all duration-200 border ';
                if($lesson->is_completed ?? false) {
                $cardClasses .= 'bg-green-50
                dark:bg-green-900/20 border-green-200
                dark:border-green-800'; }
                elseif($lesson->is_accessible ?? false) {
                $cardClasses .= 'bg-' . $color . '-50 dark:bg-'
                . $color . '-900/30 border-' . $color . '-200
                dark:border-' . $color . '-800 hover:bg-' .
                $color . '-100 dark:hover:bg-' . $color .
                '-900/50 hover:border-' . $color . '-300
                dark:hover:border-' . $color . '-600
                hover:shadow-md cursor-pointer'; } else {
                $cardClasses .= 'bg-gray-50 dark:bg-gray-700/50
                border-gray-200 dark:border-gray-600
                opacity-60'; } @endphp

                <div class="{{ $cardClasses }}">
                  <div class="flex items-start space-x-3">
                    <!-- Lesson Type Icon -->
                    <div class="flex-shrink-0">
                      @if($lessonType === 'Quiz')
                      <div class="w-8 h-8 bg-{{
                                                    $color
                                                }}-100 dark:bg-{{
                                                    $color
                                                }}-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-{{
                                                        $color
                                                    }}-600 dark:text-{{
                                                        $color
                                                    }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                      @elseif($lessonType === 'Vidéo')
                      <div class="w-8 h-8 bg-{{
                                                    $color
                                                }}-100 dark:bg-{{
                                                    $color
                                                }}-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-{{
                                                        $color
                                                    }}-600 dark:text-{{
                                                        $color
                                                    }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                          </path>
                        </svg>
                      </div>
                      @else
                      <div class="w-8 h-8 bg-{{
                                                    $color
                                                }}-100 dark:bg-{{
                                                    $color
                                                }}-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-{{
                                                        $color
                                                    }}-600 dark:text-{{
                                                        $color
                                                    }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                          </path>
                        </svg>
                      </div>
                      @endif
                    </div>

                    <!-- Lesson Content -->
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center space-x-2 mb-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-{{
                                                        $color
                                                    }}-100 text-{{
                                                        $color
                                                    }}-800 dark:bg-{{
                                                        $color
                                                    }}-900 dark:text-{{
                                                        $color
                                                    }}-200">
                          {{ $lessonType }}
                        </span>
                        @if($lesson->is_completed ??
                        false)
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                        </svg>
                        @elseif($lesson->is_current ??
                        false)
                        <button
                          class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 hover:bg-orange-200 dark:hover:bg-orange-800 transition-colors">
                          <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                              clip-rule="evenodd"></path>
                          </svg>
                          En cours
                        </button>
                        @else
                        <span
                          class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                          Locked
                        </span>
                        @endif
                      </div>
                      <h5 class="text-sm font-medium text-gray-900 dark:text-white transition-colors">
                        {{ $lesson->title }}
                      </h5>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-16">
      <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
          </path>
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        Aucun chapitre disponible
      </h3>
      <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
        Cette formation n'a pas encore de chapitres. Revenez bientôt
        pour voir les nouveaux contenus.
      </p>
    </div>
    @endif
  </div>
</div>
