<x-app-layout>
  @php
    $entryQuiz = $formation->entryQuiz;
    $quizLessons = $lessonGroups['quizzes'];
    $videoLessons = $lessonGroups['videos'];
    $textLessons = $lessonGroups['texts'];
  @endphp

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
      <div class="bg-gradient-to-br from-emerald-50 to-green-50 overflow-hidden shadow-sm sm:rounded-xl border border-emerald-100">
        <div class="p-8">
          <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
              <div class="flex items-center mb-4">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-200">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  Retour a la formation
                </a>
              </div>
              <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
                </svg>
                Gestion du contenu - {{ $formation->title }}
              </h1>
              <p class="text-gray-700 text-lg leading-relaxed mt-4">
                Accedez a une vue unifiee de vos contenus. Vous pouvez modifier ou supprimer chaque ressource sans passer par la navigation par chapitres.
              </p>
            </div>
          </div>

          <div class="grid gap-4 mt-8 md:grid-cols-3">
            <div class="bg-white rounded-xl border border-emerald-100 px-5 py-4">
              <p class="text-sm font-medium text-emerald-600 uppercase tracking-wide">Quiz de lecons</p>
              <p class="mt-3 text-3xl font-bold text-gray-900">{{ $quizLessons->count() }}</p>
              <p class="mt-1 text-sm text-gray-500">Quiz rattaches aux differentes lecons.</p>
            </div>
            <div class="bg-white rounded-xl border border-emerald-100 px-5 py-4">
              <p class="text-sm font-medium text-emerald-600 uppercase tracking-wide">Videos</p>
              <p class="mt-3 text-3xl font-bold text-gray-900">{{ $videoLessons->count() }}</p>
              <p class="mt-1 text-sm text-gray-500">Contenus video disponibles pour vos eleves.</p>
            </div>
            <div class="bg-white rounded-xl border border-emerald-100 px-5 py-4">
              <p class="text-sm font-medium text-emerald-600 uppercase tracking-wide">Textes</p>
              <p class="mt-3 text-3xl font-bold text-gray-900">{{ $textLessons->count() }}</p>
              <p class="mt-1 text-sm text-gray-500">Supports textuels et documents redactes.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="space-y-8">
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <header class="px-6 py-5 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
              <div class="bg-amber-100 text-amber-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm0 4c3.866 0 7 1.343 7 3v2H5v-2c0-1.657 3.134-3 7-3zm-2 7h4v3h-4z">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Quiz d'entree de formation</h2>
                <p class="text-sm text-gray-500">Configurer les questions et les parametres du quiz d'entree.</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
                class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg shadow hover:bg-amber-600 transition">
                Modifier le quiz d'entree
              </a>
              <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                class="inline-flex items-center px-4 py-2 border border-amber-200 text-amber-700 text-sm font-medium rounded-lg hover:bg-amber-50 transition">
                Gerer les questions
              </a>
            </div>
          </header>
          <div class="px-6 py-5">
            @if ($entryQuiz)
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $entryQuiz->title }}</h3>
                @if ($entryQuiz->description)
                <p class="text-sm text-gray-600 mt-1">{{ $entryQuiz->description }}</p>
                @endif
              </div>
              <div class="flex items-center gap-3 text-sm text-gray-500">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">
                  @php
                    $questionCount = $entryQuiz->quizQuestions()->count();
                  @endphp
                  {{ $questionCount }} question{{ $questionCount !== 1 ? 's' : '' }}
                </div>
                @if ($entryQuiz->max_attempts)
                <span>Tentatives max: {{ $entryQuiz->max_attempts }}</span>
                @endif
              </div>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm text-amber-700">
              Aucun quiz d'entree n'est configure pour le moment. Configurez-le pour accueillir vos futurs eleves.
            </div>
            @endif
          </div>
        </section>

        <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <header class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Quiz de lecons</h2>
                <p class="text-sm text-gray-500">Modifier ou supprimer les quiz rattaches aux lecons.</p>
              </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600">
              {{ $quizLessons->count() }} element{{ $quizLessons->count() !== 1 ? 's' : '' }}
            </span>
          </header>
          <div class="divide-y divide-gray-100">
            @forelse ($quizLessons as $lesson)
            <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>
                  <p class="text-sm text-gray-500 mt-1">
                    Chapitre : {{ $lesson->chapter?->title ?? 'Non assigne' }}
                  </p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('formateur.formation.chapter.lesson.quiz.edit', [$formation, $lesson->chapter, $lesson]) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition">
                  Modifier
                </a>
                <form method="POST" action="{{ route('formateur.formation.chapter.lesson.delete', [$formation, $lesson->chapter, $lesson]) }}"
                  onsubmit="return confirm('Supprimer ce quiz ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
              <p class="text-gray-500 text-sm">Aucun quiz n'est encore configure. Creez une lecon depuis la page des chapitres puis selectionnez le type "Quiz".</p>
              <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Acceder a la gestion des chapitres
              </a>
            </div>
            @endforelse
          </div>
        </section>

        <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <header class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="bg-rose-100 text-rose-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8l4 4v8a2 2 0 01-2 2H6a2 2 0 01-2-2V6z">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Videos</h2>
                <p class="text-sm text-gray-500">Gerer vos contenus video sans passer par les chapitres.</p>
              </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">
              {{ $videoLessons->count() }} element{{ $videoLessons->count() !== 1 ? 's' : '' }}
            </span>
          </header>
          <div class="divide-y divide-gray-100">
            @forelse ($videoLessons as $lesson)
            <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>
                  <p class="text-sm text-gray-500 mt-1">
                    Chapitre : {{ $lesson->chapter?->title ?? 'Non assigne' }}
                    @if ($lesson->lessonable && $lesson->lessonable->duration_minutes)
                    <span class="mx-2 text-gray-300">|</span>
                    Duree : {{ $lesson->lessonable->duration_minutes }} min
                    @endif
                  </p>
                  @if ($lesson->lessonable && $lesson->lessonable->video_url)
                  <p class="text-xs text-gray-400 break-all mt-1">
                    {{ $lesson->lessonable->video_url }}
                  </p>
                  @endif
                </div>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('formateur.formation.chapter.lesson.video.edit', [$formation, $lesson->chapter, $lesson]) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-rose-700 border border-rose-200 rounded-lg hover:bg-rose-50 transition">
                  Modifier
                </a>
                <form method="POST" action="{{ route('formateur.formation.chapter.lesson.delete', [$formation, $lesson->chapter, $lesson]) }}"
                  onsubmit="return confirm('Supprimer cette video ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
              <p class="text-gray-500 text-sm">Vous n'avez pas encore ajoute de video. Creez une lecon et choisissez le type "Video" pour commencer.</p>
              <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-rose-600 hover:text-rose-800">
                Acceder a la gestion des chapitres
              </a>
            </div>
            @endforelse
          </div>
        </section>

        <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <header class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
                </svg>
              </div>
              <div>
                <h2 class="text-xl font-semibold text-gray-900">Textes</h2>
                <p class="text-sm text-gray-500">Visualisez vos lecons textuelles et leurs documents associes.</p>
              </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">
              {{ $textLessons->count() }} element{{ $textLessons->count() !== 1 ? 's' : '' }}
            </span>
          </header>
          <div class="divide-y divide-gray-100">
            @forelse ($textLessons as $lesson)
            <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>
                  <p class="text-sm text-gray-500 mt-1">
                    Chapitre : {{ $lesson->chapter?->title ?? 'Non assigne' }}
                    @if ($lesson->lessonable && $lesson->lessonable->estimated_read_time)
                    <span class="mx-2 text-gray-300">|</span>
                    Lecture estimee : {{ $lesson->lessonable->estimated_read_time }} min
                    @endif
                  </p>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('formateur.formation.chapter.lesson.text.edit', [$formation, $lesson->chapter, $lesson]) }}"
                  class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                  Modifier
                </a>
                <form method="POST" action="{{ route('formateur.formation.chapter.lesson.delete', [$formation, $lesson->chapter, $lesson]) }}"
                  onsubmit="return confirm('Supprimer ce contenu texte ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                    Supprimer
                  </button>
                </form>
              </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
              <p class="text-gray-500 text-sm">Aucun contenu texte n'est disponible. Creez une lecon et selectionnez le type "Texte" pour ajouter un article ou un support.</p>
              <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-blue-600 hover:text-blue-800">
                Acceder a la gestion des chapitres
              </a>
            </div>
            @endforelse
          </div>
        </section>
      </div>
    </div>
  </div>
</x-app-layout>
