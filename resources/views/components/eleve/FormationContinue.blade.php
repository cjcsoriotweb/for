<section class="space-y-6">
  <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400/70">Continue watching</p>
      <h2 class="text-2xl font-semibold text-white sm:text-3xl">Continuer mes formations</h2>
    </div>

    @if($formationsWithProgress->count() > 0)
    <a
      href="{{ route('eleve.formation.available', [$team]) }}"
      class="inline-flex items-center gap-2 text-sm font-medium text-slate-300 transition hover:text-white"
    >
      Voir toutes les formations
      <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
      </svg>
    </a>
    @endif
  </div>

  @if($formationsWithProgress->count() > 0)
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="flex snap-x snap-mandatory gap-6 overflow-x-auto px-4 pb-6 sm:px-2">
      @foreach($formationsWithProgress as $formation)
      @php
          $progressPercent = (int) ($formation->progress_data['progress_percent'] ?? 0);
          $isCompleted = (bool) ($formation->is_completed ?? false);
          $statusColor = $isCompleted ? 'from-emerald-400/80 via-emerald-500/60 to-emerald-500/40' : 'from-blue-500/90 via-indigo-500/70 to-purple-500/60';
      @endphp

      <article class="group relative isolate flex min-w-[260px] max-w-xs snap-start flex-col justify-between overflow-hidden rounded-3xl border border-white/15 bg-slate-900/55 transition duration-300 hover:-translate-y-1 hover:border-white/35 hover:shadow-[0_35px_120px_-45px_rgba(129,140,248,0.5)]">
        <div class="absolute inset-0 bg-gradient-to-br {{ $statusColor }} opacity-60 transition group-hover:opacity-80"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.42),transparent_58%)] opacity-30 mix-blend-screen"></div>

        <div class="relative space-y-4 p-6">
          <div class="flex items-center justify-between">
            <span class="rounded-full border border-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/80">
              {{ $isCompleted ? 'Termin&eacute;' : 'En cours' }}
            </span>
            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80">
              {{ $progressPercent }}%
            </span>
          </div>

          <div class="space-y-3">
            <h3 class="text-lg font-semibold leading-snug text-white line-clamp-2">
              {{ $formation->title }}
            </h3>
            <p class="text-sm text-slate-100/80 line-clamp-3">
              {{ $formation->description ?? 'Poursuivez votre apprentissage et d&eacute;bloquez le prochain chapitre.' }}
            </p>
          </div>
        </div>

        <div class="relative space-y-4 border-t border-white/10 px-6 py-4 backdrop-blur">
          <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
            <div class="h-full rounded-full bg-white/80 transition-all duration-500" style="width: {{ $isCompleted ? 100 : $progressPercent }}%;"></div>
          </div>

          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-white/60">
            <span>Progression</span>
            <span>{{ $progressPercent }}%</span>
          </div>

          <a
            href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
            class="group/btn inline-flex items-center justify-center gap-2 rounded-2xl bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30"
          >
            {{ $isCompleted ? 'Revoir la formation' : 'Continuer' }}
            <svg class="h-4 w-4 transition group-hover/btn:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
              <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </article>
      @endforeach
    </div>
  </div>
  @else
  <div class="rounded-3xl border border-dashed border-white/15 bg-white/5 px-8 py-14 text-center text-slate-200/80">
    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-white/10">
      <svg class="h-10 w-10 text-white/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7v10m0-10c-.421-.505-1.892-1.5-3.764-1.5C6.057 5.5 4.5 6.703 4.5 8.25V17c1.557-1.297 3.114-2.5 3.736-2.5 1.872 0 3.343.995 3.764 1.5m0-8c.421-.505 1.892-1.5 3.764-1.5 2.179 0 3.736 1.203 3.736 2.75V17c-1.557-1.297-3.114-2.5-3.736-2.5-1.872 0-3.343.995-3.764 1.5" />
      </svg>
    </div>
    <h3 class="text-xl font-semibold text-white">Aucune formation en cours</h3>
    <p class="mt-3 text-sm text-slate-300/80">
      Parcourez le catalogue des formations disponibles et lancez votre prochain apprentissage.
    </p>
    <a
      href="{{ route('eleve.formation.available', [$team]) }}"
      class="mt-6 inline-flex items-center gap-2 rounded-full bg-white/15 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/25"
    >
      Explorer les formations
      <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
      </svg>
    </a>
  </div>
  @endif
</section>
