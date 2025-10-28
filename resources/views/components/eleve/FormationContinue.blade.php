<section class="space-y-6">
  <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400/70">Continuez vos formations</p>
      <h2 class="text-2xl font-semibold text-white sm:text-3xl">Continuer mes formations</h2>
    </div>
  </div>

  @if($formationsWithProgress->count() > 0)
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="flex snap-x snap-mandatory gap-6 overflow-x-auto px-4 pb-6 sm:px-2">
      @foreach($formationsWithProgress as $formation)
      @php
      $progressPercent = (int) ($formation->progress_data['progress_percent'] ?? 0);
      $isCompleted = (bool) ($formation->is_completed ?? false);
      $fallbackTitle = $formation->title ?: 'Titre par d&eacute;faut';
      $fallbackDescription = $formation->description ?: 'Description par d&eacute;faut';
      @endphp

      <article
        class="group relative isolate flex min-w-[260px] max-w-xs snap-start flex-col justify-between overflow-hidden rounded-3xl border border-white/15 bg-slate-900/60 transition duration-300 hover:-translate-y-1 ">
        <div class="relative h-36 w-full overflow-hidden border-b border-white/10 bg-white/5 sm:h-40">
          <img src="{{ $formation->cover_image_url }}" alt="Image de couverture de {{ $fallbackTitle }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
        </div>

        <div class="relative space-y-4 p-6">
          <div class="flex items-center justify-between">
            <span
              class="rounded-full border border-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/80">
              {{ $isCompleted ? 'Terminé' : 'Continuer' }}
            </span>
            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-white/80">
              {{ $progressPercent }}%
            </span>
          </div>

          <div class="space-y-3">
            <h3 class="text-lg font-semibold leading-snug text-white line-clamp-2">
              {{ $fallbackTitle }}
            </h3>
            <p class="text-sm text-slate-100/80 line-clamp-3">
              {{ $fallbackDescription }}
            </p>
          </div>
        </div>

        <div class="relative space-y-4 border-t border-white/10 px-6 py-4 backdrop-blur">
          <div class="h-2 w-full overflow-hidden rounded-full bg-white/20">
            <div class="h-full rounded-full bg-white/80 transition-all duration-500"
              style="width: {{ $isCompleted ? 100 : $progressPercent }}%;"></div>
          </div>

          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-white/60">
            <span>Progression :</span>
            <span>{{ $progressPercent }}%</span>
          </div>

          <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
            class="group/btn inline-flex items-center justify-center gap-2 rounded-2xl bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30">
            {{ $isCompleted ? 'Revoir la formation' : 'Voir les détails' }}
            <svg class="h-4 w-4 transition group-hover/btn:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor"
              aria-hidden="true">
              <path fill-rule="evenodd"
                d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
              <path fill-rule="evenodd"
                d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z"
                clip-rule="evenodd" />
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
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M12 7v10m0-10c-.421-.505-1.892-1.5-3.764-1.5C6.057 5.5 4.5 6.703 4.5 8.25V17c1.557-1.297 3.114-2.5 3.736-2.5 1.872 0 3.343.995 3.764 1.5m0-8c.421-.505 1.892-1.5 3.764-1.5 2.179 0 3.736 1.203 3.736 2.75V17c-1.557-1.297-3.114-2.5-3.736-2.5-1.872 0-3.343.995-3.764 1.5" />
      </svg>
    </div>
    <h3 class="text-xl font-semibold text-white">Aucune formation en cours</h3>
    <p class="mt-3 text-sm text-slate-300/80">
      Demandez à votre organisme de vous inscrire à une formation pour commencer votre parcours.
    </p>
  </div>
  @endif
</section>
