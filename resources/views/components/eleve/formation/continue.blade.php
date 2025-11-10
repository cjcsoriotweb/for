@props(['availableFormationsCount' => 0])

<section class="space-y-8">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <div class="flex items-center gap-3 mb-2">
        <div class="h-1 w-6 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full"></div>
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-600/70">Continuez vos formations</p>
      </div>
      <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">
        Continuer mes formations
      </h2>
      <p class="mt-2 max-w-2xl text-sm text-slate-600/80 leading-relaxed">
        Retrouvez vos formations en cours, suivez votre progression et reprenez chaque module au meilleur moment pour
        vous.
      </p>
    </div>
  </div>

  @if($availableFormationsCount === 0)
  <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-4">
    <div class="flex items-start gap-3">
      <div class="flex-shrink-0">
        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-sm font-medium text-amber-800">Pas d'inquiétude, aucune formation disponible pour l'équipe</h3>
        <p class="mt-1 text-sm text-amber-700">
          S'il s'agit d'une erreur contactez-vous (<a href="{{ route('user.tickets') }}" class="underline hover:text-amber-800">page ticket</a>).
        </p>
      </div>
    </div>
  </div>
  @endif

  @if($formationsWithProgress->count() > 0)
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="grid grid-cols-1 gap-5 px-4 pb-4 sm:px-2 sm:grid-cols-2">
      @foreach($formationsWithProgress as $formation)
    @php
    $progressPercent = (int) ($formation->progress_data['progress_percent'] ?? 0);
    $isCompleted = (bool) ($formation->is_completed ?? false);
    $isPendingValidation = (bool) ($formation->is_pending_validation ?? false);
    $isValidated = (bool) ($formation->is_validated ?? false);
    $isRejected = ($formation->validation_status ?? '') === 'rejected';
    $fallbackTitle = $formation->title ?: 'Titre par d&eacute;faut';
    $fallbackDescription = $formation->description ?: 'Description par d&eacute;faut';
    @endphp

      <article 
        class="group flex w-full flex-col overflow-hidden rounded-xl border {{ $isCompleted ? 'border-green-600 bg-white' : 'border-slate-200 bg-white' }} transition-colors duration-200 hover:border-slate-300">
        <div class="h-24 w-full overflow-hidden border-b border-slate-200 bg-slate-50 sm:h-28">
          <img src="{{ $formation->cover_image_url }}" alt="Image de couverture de {{ $fallbackTitle }}"
            class="h-full w-full object-cover" loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
        </div>

        <div class="space-y-3 p-4">
    @if($isCompleted)
      @if($isPendingValidation || $isRejected)
      <div class="flex items-center justify-center">
        @if($isPendingValidation)
        <div class="flex items-center gap-2 rounded-full bg-amber-600/20 border border-amber-600 px-4 py-2 text-sm font-medium text-amber-400">
          <svg class="h-4 w-4 animate-spin" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
          </svg>
          En attente de validation
        </div>
        @elseif($isRejected)
        <div class="flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700">
          <svg class="h-4 w-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span>Demande rejetée</span>
        </div>
        @endif
      </div>
      @endif
    @else
          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-slate-600">
            <span class="rounded-full border border-slate-300 bg-slate-100 px-3 py-1 text-slate-700">
              Continuer
            </span>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">
              {{ $progressPercent }}&nbsp;%
            </span>
          </div>
          @endif

          <div class="space-y-3 text-slate-700">
            <h3 class="text-base font-semibold leading-snug text-slate-900 line-clamp-2">
              {{ $fallbackTitle }}
            </h3>
            <p class="text-sm text-slate-600/80 line-clamp-3">
              {{ $fallbackDescription }}
            </p>
          </div>
        </div>

        <div class="space-y-3 border-t border-slate-200 px-4 py-3">
          @php
            $moduleCount = $formation->lessons_count ?? $formation->lessons()->count();
          @endphp
          <div class="flex items-center justify-between text-xs font-medium text-slate-600">
            <span>Modules</span>
            <span>{{ $moduleCount }}</span>
          </div>
          @if($isCompleted)
          @if($isPendingValidation)
          <div class="flex items-center justify-center">
            <div class="flex items-center gap-2 text-amber-400">
              <svg class="h-5 w-5 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
              </svg>
              <span class="text-sm font-medium">En attente de validation par un administrateur</span>
            </div>
          </div>

          <div class="space-y-3">
            <div class="text-center text-xs text-slate-400">
              Un superadmin doit valider votre formation avant que vous puissiez accéder à votre certificat.
            </div>

            <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
              class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
              </svg>
              Revoir la formation
            </a>
          </div>
          @else
          <div class="space-y-3">
            @if($isRejected)
            <div class="rounded-2xl border border-red-200 bg-red-50/80 px-4 py-3 text-sm text-red-700">
              <p class="font-semibold">Votre demande de validation a été rejetée.</p>
              <p class="text-xs text-red-600/80">Revoyez la formation ou contactez un administrateur pour soumettre une nouvelle demande.</p>
            </div>
            <div class="space-y-2">
              <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50/70 px-3 py-1.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                  <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
                </svg>
                Revoir la formation
              </a>
              <a href="{{ route('user.tickets') }}"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-transparent px-3 py-1.5 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7.414a2 2 0 00-.586-1.414l-3.414-3.414A2 2 0 0011.586 2H5z" />
                  <path d="M9 7a1 1 0 100 2h2a1 1 0 100-2H9z" />
                </svg>
                Contester le refus
              </a>
            </div>
            @endif

            @if(!$isRejected)
              @if($isValidated)
                <a href="{{ route('eleve.formation.completed', [$team, $formation->id]) }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4.632 3.533A2 2 0 016.577 2h6.846a2 2 0 011.945 1.533l1.976 8.234A3.489 3.489 0 0016 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234z" />
                    <path fill-rule="evenodd" d="M4 13a2 2 0 100 4h12a2 2 0 100-4H4zm11.24 2a.75.75 0 01.75-.75H16a.75.75 0 01.75.75v.01a.75.75 0 01-.75.75h-.01a.75.75 0 01-.75-.75V15zm-2.25-.75a.75.75 0 00-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 00.75-.75V15a.75.75 0 00-.75-.75h-.01z" clip-rule="evenodd" />
                  </svg>
                  Voir mon certificat
                </a>
              @elseif($progressPercent === 100)
                <form method="POST" action="{{ route('eleve.formation.request-completion', [$team, $formation->id]) }}">
                  @csrf
                  <button type="submit"
                          class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10.075 2.678a.75.75 0 01.85 0l6 4a.75.75 0 01.075 1.2l-6 5a.75.75 0 01-.95 0l-6-5A.75.75 0 013.075 6.68l6-4zM4 8.25v5.5A2.25 2.25 0 006.25 16h7.5A2.25 2.25 0 0016 13.75v-5.5l-5.225 4.354a2.25 2.25 0 01-2.85 0L4 8.25z" clip-rule="evenodd" />
                    </svg>
                    Faire valider ma formation
                  </button>
                </form>
              @endif
            @endif
          </div>
          @endif
          @else
          <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
            <div class="h-full rounded-full bg-slate-600 transition-all duration-300"
              style="width: {{ $progressPercent }}%;"></div>
          </div>

          <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-slate-600">
            <span>Progression</span>
            <span>{{ $progressPercent }}&nbsp;%</span>
          </div>

          <a href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-950 transition hover:bg-white">
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
  <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-8 py-12 text-center text-slate-600">
    <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-slate-200">
      <svg class="h-8 w-8 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M12 7v10m0-10c-.421-.505-1.892-1.5-3.764-1.5C6.057 5.5 4.5 6.703 4.5 8.25V17c1.557-1.297 3.114-2.5 3.736-2.5 1.872 0 3.343.995 3.764 1.5m0-8c.421-.505 1.892-1.5 3.764-1.5 2.179 0 3.736 1.203 3.736 2.75V17c-1.557-1.297-3.114-2.5-3.736-2.5-1.872 0-3.343.995-3.764 1.5" />
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900">Aucune formation en cours</h3>
    <p class="mt-3 text-sm text-slate-500">
      Demandez &agrave; votre organisme de vous inscrire &agrave; une formation pour commencer votre parcours.
    </p>
  </div>
  @endif
</section>
