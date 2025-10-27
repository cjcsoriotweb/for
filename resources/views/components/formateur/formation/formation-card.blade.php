@props(['formation'])

<div
  class="group relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/60 hover:border-blue-200/60 hover:-translate-y-2">
  <!-- Decorative layers -->
  <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-indigo-50/30 to-purple-50/50"></div>
  <div
    class="absolute inset-0 bg-gradient-to-r from-blue-500/3 via-transparent to-purple-500/3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
  </div>

  <!-- Animated background elements -->
  <div
    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-2xl transform translate-x-16 -translate-y-16 group-hover:translate-x-12 group-hover:-translate-y-12 transition-transform duration-700">
  </div>
  <div
    class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-indigo-400/10 to-cyan-400/10 rounded-full blur-xl transform -translate-x-12 translate-y-12 group-hover:-translate-x-8 group-hover:translate-y-8 transition-transform duration-700">
  </div>

  <div class="relative z-10 h-44 w-full overflow-hidden border-b border-white/40 bg-white/50">
    <img
      src="{{ $formation->cover_image_url }}"
      alt="Image de couverture de {{ $formation->title }}"
      class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
      loading="lazy"
      onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
    />
  </div>

  <div class="relative z-10 p-8">
    <div class="flex items-start justify-between mb-6">
      <div class="flex-1">
        <div class="flex items-center space-x-3 mb-3">
          <h3
            class="font-bold text-3xl text-gray-900 leading-tight group-hover:text-blue-900 transition-colors duration-300">
            {{ $formation->title }}
          </h3>
          <div class="flex items-center space-x-2">
            <span
              @class([
                'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border',
                'bg-emerald-100 text-emerald-800 border-emerald-200' => $formation->card_is_active,
                'bg-gray-100 text-gray-700 border-gray-200' => ! $formation->card_is_active,
              ])>
              <div class="w-2 h-2 rounded-full mr-2 animate-pulse"
                @class([
                  'bg-emerald-500' => $formation->card_is_active,
                  'bg-gray-400' => ! $formation->card_is_active,
                ])></div>
              {{ $formation->card_is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
        <div class="flex items-center space-x-3 text-sm text-gray-500">
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $formation->card_created_label ?? '--' }}</span>
          </div>
          <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>{{ $formation->card_learners_count }} etudiants</span>
          </div>
        </div>
      </div>
      <div class="flex-shrink-0">
        <div class="relative">
          <div class="w-4 h-4 bg-gradient-to-r from-emerald-400 to-green-500 rounded-full animate-pulse shadow-lg">
          </div>
          <div
            class="absolute inset-0 w-4 h-4 bg-gradient-to-r from-emerald-400 to-green-500 rounded-full animate-ping opacity-30">
          </div>
        </div>
      </div>
    </div>

    <div class="mb-8">
      <p class="text-gray-700 text-lg leading-relaxed line-clamp-2 mb-4">
        {{ $formation->card_description }}
      </p>
      <div class="w-full bg-gray-200 rounded-full h-1 overflow-hidden">
        <div
          class="bg-gradient-to-r from-blue-500 to-indigo-500 h-1 rounded-full transition-all duration-1000 progress-bar"
          data-width="{{ $formation->card_completion_percentage }}"></div>
      </div>
    </div>

    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <a href="{{ route('formateur.formation.show', $formation) }}"
          class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
          Voir les details
        </a>

        <div
          class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
          <button
            class="p-2 bg-white/80 hover:bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-200/60">
            <svg class="w-4 h-4 text-gray-600 hover:text-blue-600 transition-colors duration-200" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
              </path>
            </svg>
          </button>
          <button
            class="p-2 bg-white/80 hover:bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-200/60">
            <svg class="w-4 h-4 text-gray-600 hover:text-green-600 transition-colors duration-200" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
              </path>
            </svg>
          </button>
        </div>
      </div>

      <div class="flex items-center space-x-6 text-sm">
        @if($formation->card_lessons_count > 0)
        <div class="flex items-center space-x-2 px-3 py-2 bg-white/60 rounded-xl border border-gray-200/60">
          <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="font-medium text-gray-700">{{ $formation->card_lessons_count }} lecons</span>
        </div>
        @endif

        @if($formation->total_duration_minutes > 0)
        <div class="flex items-center space-x-2 px-3 py-2 bg-white/60 rounded-xl border border-gray-200/60">
          <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span class="font-medium text-gray-700">{{ $formation->total_duration_minutes }}min</span>
        </div>
        @endif

        @if($formation->video_count > 0 || $formation->quiz_count > 0 || $formation->text_count > 0)
        <div class="flex items-center space-x-2">
          @if($formation->video_count > 0)
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 border border-red-200">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z">
              </path>
            </svg>
            {{ $formation->video_count }}
          </span>
          @endif

          @if($formation->quiz_count > 0)
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $formation->quiz_count }}
          </span>
          @endif

          @if($formation->text_count > 0)
          <span
            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
            {{ $formation->text_count }}
          </span>
          @endif
        </div>
        @endif
      </div>
    </div>
  </div>

  <div
    class="absolute inset-0 rounded-3xl ring-2 ring-blue-500/0 group-hover:ring-blue-500/30 transition-all duration-500 pointer-events-none">
  </div>
  <div
    class="absolute inset-0 rounded-3xl bg-gradient-to-r from-blue-600/5 via-purple-600/5 to-indigo-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
  </div>
</div>
