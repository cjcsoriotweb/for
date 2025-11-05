<section class="space-y-8">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-300/70">Continuez vos formations</p>
      <h2 class="text-2xl font-semibold text-white sm:text-3xl">Continuer mes formations</h2>
      <p class="mt-2 max-w-2xl text-sm text-slate-300/75">
        Retrouvez vos formations en cours, suivez votre progression et reprenez chaque module au meilleur moment pour
        vous.
      </p>
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
        class="group flex min-w-[260px] max-w-xs snap-start flex-col overflow-hidden rounded-2xl border {{ $isCompleted ? 'border-green-600 bg-slate-900/80' : 'border-slate-800 bg-slate-900/70' }} transition-colors duration-200 hover:border-slate-700">
        <div class="h-36 w-full overflow-hidden border-b border-slate-800 bg-slate-900 sm:h-40">
          <img src="{{ $formation->cover_image_url }}" alt="Image de couverture de {{ $fallbackTitle }}"
            class="h-full w-full object-cover" loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
        </div>

        <div class="space-y-5 p-6">
          @if($isCompleted)
          <div class="flex items-center justify-center">
            <div class="flex items-center gap-2 rounded-full bg-green-600/20 border border-green-600 px-4 py-2 text-sm font-medium text-green-400">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Formation terminée
            </div>
          </div>
          @else
          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-slate-300">
            <span class="rounded-full border border-slate-700 bg-slate-800 px-3 py-1 text-slate-200">
              Continuer
            </span>
            <span class="rounded-full bg-slate-800 px-3 py-1 text-slate-200">
              {{ $progressPercent }}&nbsp;%
            </span>
          </div>
          @endif

          <div class="space-y-3 text-slate-200">
            <h3 class="text-lg font-semibold leading-snug text-white line-clamp-2">
              {{ $fallbackTitle }}
            </h3>
            <p class="text-sm text-slate-100/80 line-clamp-3">
              {{ $fallbackDescription }}
            </p>
          </div>
        </div>

        <div class="space-y-4 border-t border-slate-800 px-6 py-5">
          @if($isCompleted)
          <div class="flex items-center justify-center">
            <div class="flex items-center gap-2 text-green-400">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <span class="text-sm font-medium">Formation réussie à 100%</span>
            </div>
          </div>

          <div class="space-y-3">
            <a href="{{ route('eleve.formation.completed', [$team, $formation->id]) }}"
              class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4.632 3.533A2 2 0 016.577 2h6.846a2 2 0 011.945 1.533l1.976 8.234A3.489 3.489 0 0016 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234z" />
                <path fill-rule="evenodd" d="M4 13a2 2 0 100 4h12a2 2 0 100-4H4zm11.24 2a.75.75 0 01.75-.75H16a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75h-.01a.75.75 0 01-.75-.75V15zm-2.25-.75a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-.01z" clip-rule="evenodd" />
              </svg>
              Voir mon certificat
            </a>

            <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
              class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-600 bg-slate-800 px-4 py-2 text-sm font-semibold text-slate-200 transition hover:bg-slate-700">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
              </svg>
              Revoir la formation
            </a>
          </div>
          @else
          <div class="h-2 w-full overflow-hidden rounded-full bg-slate-800">
            <div class="h-full rounded-full bg-slate-200 transition-all duration-300"
              style="width: {{ $progressPercent }}%;"></div>
          </div>

          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-slate-300">
            <span>Progression</span>
            <span>{{ $progressPercent }}&nbsp;%</span>
          </div>

          <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-white">
            Voir les détails
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" />
              <path fill-rule="evenodd"
                d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z"
                clip-rule="evenodd" />
            </svg>
          </a>
          @endif
        </div>
      </article>
      @endforeach
    </div>
  </div>
  @else
  <div class="rounded-2xl border border-dashed border-slate-800 bg-slate-900/60 px-8 py-12 text-center text-slate-300">
    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-slate-800/70">
      <svg class="h-8 w-8 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M12 7v10m0-10c-.421-.505-1.892-1.5-3.764-1.5C6.057 5.5 4.5 6.703 4.5 8.25V17c1.557-1.297 3.114-2.5 3.736-2.5 1.872 0 3.343.995 3.764 1.5m0-8c.421-.505 1.892-1.5 3.764-1.5 2.179 0 3.736 1.203 3.736 2.75V17c-1.557-1.297-3.114-2.5-3.736-2.5-1.872 0-3.343.995-3.764 1.5" />
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-white">Aucune formation en cours</h3>
    <p class="mt-3 text-sm text-slate-300/80">
      Demandez &agrave; votre organisme de vous inscrire &agrave; une formation pour commencer votre parcours.
    </p>
  </div>
  @endif
</section>
