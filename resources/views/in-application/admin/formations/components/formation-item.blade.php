<article x-data="{ open: false }" class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50/70 p-6 shadow-md ring-1 ring-white/70">
  @php
    $usageQuota = $usagePivot->usage_quota ?? null;
    $usageConsumed = $usagePivot->usage_consumed ?? 0;
    $usageRemaining = is_null($usageQuota) ? null : max($usageQuota - $usageConsumed, 0);
    $chapterCount = $formation->chapters_count ?? $formation->chapters?->count() ?? 0;
    $lessonCount = $formation->lessons_count ?? $formation->lessons?->count() ?? 0;
    $videoCount = $formation->video_lessons_count ?? 0;
    $quizCount = $formation->quiz_lessons_count ?? 0;
    $textCount = $formation->text_lessons_count ?? 0;
    $lessonTypeMeta = [
        \App\Models\VideoContent::class => ['label' => 'Video', 'class' => 'bg-sky-100 text-sky-700'],
        \App\Models\Quiz::class => ['label' => 'Quiz', 'class' => 'bg-indigo-100 text-indigo-700'],
        \App\Models\TextContent::class => ['label' => 'Texte', 'class' => 'bg-emerald-100 text-emerald-700'],
    ];
    $modulePreview = collect($formation->chapters ?? [])
        ->flatMap(function ($chapter) {
            return collect($chapter->lessons ?? [])->map(function ($lesson) use ($chapter) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title ?: ('Module #'.$lesson->id),
                    'type' => $lesson->lessonable_type,
                    'chapter' => $chapter->title ?? ('Chapitre '.$chapter->position),
                ];
            });
        })
        ->values()
        ->take(4);
  @endphp
  <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-sm">
    <button type="button" class="flex w-full flex-col gap-6 text-left md:flex-row md:items-center" @click="open = !open">
      <figure class="w-full max-w-xs shrink-0 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 shadow-inner">
        <img
          src="{{ $formation->cover_image_url }}"
          alt="{{ __('Illustration de la formation : :title', ['title' => $formation->title]) }}"
          class="aspect-[4/3] w-full object-cover"
          loading="lazy"
        >
      </figure>
      <div class="flex flex-1 flex-wrap items-start justify-between gap-4">
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <span class="inline-flex size-9 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-600 shadow-inner">
              {{ $loop->iteration }}
            </span>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Formation</p>
          </div>
          <h3 class="text-2xl font-semibold text-slate-900">{{ $formation->title }}</h3>
        </div>
        <div class="flex flex-col items-end gap-2 text-right">
          <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
            <span class="size-2 rounded-full {{ $formation->is_visible ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            {{ $formation->is_visible ? 'Visible' : 'Masquee' }}
          </span>
          <span class="text-xs text-slate-500">ID #{{ $formation->id }}</span>
          <span class="inline-flex items-center gap-2 text-xs font-semibold text-indigo-600">
            <span x-text="open ? 'Masquer les details' : 'Afficher les details'"></span>
            <svg class="size-4 transition-transform duration-200" :class="{ 'rotate-180': open }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.25a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08z" clip-rule="evenodd" />
            </svg>
          </span>
        </div>
      </div>
    </button>
  </div>

  <div x-show="open" x-transition.opacity.duration.200ms class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)]" x-cloak>
    <section class="space-y-6">
      <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-sm">
        @if ($formation->description)
          <p class="text-sm leading-relaxed text-slate-600">
            {{ $formation->description }}
          </p>
        @else
          <p class="text-sm italic text-slate-500">Aucune description renseignee pour le moment.</p>
        @endif
      </div>

      <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Chapitres</p>
          <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $chapterCount }}</p>
          <p class="text-xs text-slate-400">Organisation du parcours</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Modules</p>
          <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $lessonCount }}</p>
          <p class="text-xs text-slate-400">Total des contenus</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-sm">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Repartition</p>
          <div class="mt-2 flex flex-wrap gap-2 text-sm">
            <span class="inline-flex items-center gap-1 rounded-full bg-sky-50 px-2 py-1 font-semibold text-sky-700">
              <span class="size-1.5 rounded-full bg-sky-400"></span>{{ $videoCount }} video{{ $videoCount > 1 ? 's' : '' }}
            </span>
            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 font-semibold text-indigo-700">
              <span class="size-1.5 rounded-full bg-indigo-400"></span>{{ $quizCount }} quiz
            </span>
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 font-semibold text-emerald-700">
              <span class="size-1.5 rounded-full bg-emerald-400"></span>{{ $textCount }} texte{{ $textCount > 1 ? 's' : '' }}
            </span>
          </div>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Modules presents</p>
            <p class="text-sm text-slate-400">Apercu des 4 prochains contenus</p>
          </div>
          <span class="text-xs font-semibold text-slate-500">
            {{ $lessonCount }} module{{ $lessonCount > 1 ? 's' : '' }}
          </span>
        </div>

        @if ($modulePreview->isNotEmpty())
          <ul class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ($modulePreview as $module)
              @php
                $typeMeta = $lessonTypeMeta[$module['type']] ?? ['label' => 'Module', 'class' => 'bg-slate-200 text-slate-700'];
              @endphp
              <li class="rounded-xl border border-slate-100 bg-slate-50/60 p-3 shadow-inner">
                <div class="flex items-center justify-between gap-3">
                  <p class="font-semibold text-slate-900">{{ $module['title'] }}</p>
                  <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $typeMeta['class'] }}">
                    {{ $typeMeta['label'] }}
                  </span>
                </div>
                <p class="mt-1 text-xs text-slate-500">{{ $module['chapter'] }}</p>
              </li>
            @endforeach
          </ul>
          @if ($lessonCount > $modulePreview->count())
            <p class="mt-4 text-xs text-slate-500">
              ... et {{ $lessonCount - $modulePreview->count() }} module(s) supplementaire(s)
            </p>
          @endif
        @else
              <p class="mt-4 text-sm text-slate-500">Aucun module n'a encore ete ajoute.</p>
        @endif
      </div>
    </section>

    <aside class="space-y-4">
      <div class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-4 text-white shadow-lg">
        <p class="text-xs font-semibold uppercase tracking-wide text-white/70">Statut</p>
        <p class="mt-1 text-lg font-semibold">
          {{ $formation->is_visible ? 'Formation active' : 'Formation masquee' }}
        </p>
        <p class="text-sm text-white/80">
          @if ($formation->is_visible)
            Visible pour l'equipe, quota applique ci-dessous.
          @else
            Rendez-la visible pour permettre son acces aux apprenants.
          @endif
        </p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gestion des utilisations</p>
        <div class="mt-3 space-y-2 text-sm text-slate-600">
          @if ($formation->is_visible)
            @if (is_null($usageQuota))
              <p class="text-base font-semibold text-emerald-600">Utilisation illimitee</p>
            @else
              <p>
                <span class="text-lg font-semibold text-slate-900">{{ $usageRemaining }}</span>
                restant{{ $usageRemaining === 1 ? '' : 's' }}
              </p>
              <p class="text-xs text-slate-400">
                sur {{ $usageQuota }} autorisee{{ $usageQuota === 1 ? '' : 's' }}
              </p>
            @endif
            <p class="text-xs text-slate-500">
              {{ $usageConsumed }} utilisation{{ $usageConsumed === 1 ? '' : 's' }} consommee{{ $usageConsumed === 1 ? '' : 's' }}
            </p>
          @else
            <p class="text-sm text-slate-500">Formation non activee.</p>
          @endif
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm">
        @include('in-application.admin.formations.parts.buttons.forms.edit-visibility-formation', [
            'usagePivot' => $usagePivot,
        ])
      </div>
    </aside>
  </div>
</article>
