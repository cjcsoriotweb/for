@php
    $formations = collect($currentFormation ?? []);
    $nextFormation = $formations->first(fn ($formation) => empty($formation->is_completed));
    $featuredFormation = $nextFormation ?? $formations->first();
    $inProgressCount = $formations->filter(fn ($formation) => empty($formation->is_completed))->count();
@endphp

<section class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-[0_40px_120px_-50px_rgba(59,130,246,0.6)]">
  <div class="absolute inset-0">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/85 to-slate-900/10"></div>
    <img
      src="{{ $team->profile_photo_url }}"
      alt="{{ $team->name }}"
      class="h-full w-full object-cover opacity-30 mix-blend-luminosity"
    />
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.45),transparent_60%)]"></div>
  </div>

  <div class="relative z-10 flex flex-col gap-12 px-6 py-14 sm:px-10 lg:flex-row lg:items-end lg:justify-between lg:px-16">
    <div class="max-w-2xl space-y-6">
      <p class="text-sm uppercase tracking-[0.35em] text-slate-200/70">Espace apprenant</p>
      <h1 class="text-3xl font-semibold text-white sm:text-4xl md:text-5xl">
        Bonjour {{ Auth::user()->name }}&nbsp;!
      </h1>
      <p class="text-lg text-slate-200/80 sm:text-xl">
        Pr&ecirc;t &agrave; reprendre l&agrave; o&ugrave; vous vous &ecirc;tes arr&ecirc;t&eacute; ? Choisissez une formation et poursuivez votre parcours au rythme qui vous convient.
      </p>

      <div class="flex flex-wrap gap-6 pt-4">
        <div>
          <div class="text-4xl font-semibold text-white">
            {{ $inProgressCount }}
          </div>
          <p class="text-sm uppercase tracking-wide text-slate-300/70">Formations en cours</p>
        </div>
        <div>
          <div class="text-4xl font-semibold text-white">
            {{ $formations->count() }}
          </div>
          <p class="text-sm uppercase tracking-wide text-slate-300/70">Formations suivies</p>
        </div>
      </div>

      @if($featuredFormation)
      <div class="flex flex-wrap items-center gap-4 pt-6">
        <a
          href="{{ route('eleve.formation.show', [$team, $featuredFormation->id]) }}"
          class="inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-500/25 transition hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-500/35"
        >
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M4.5 3.5a.75.75 0 0 1 .75-.75h.5A4.25 4.25 0 0 1 10 3.75h.354a5.25 5.25 0 0 1 5.146 4.208c.03.166.05.335.07.504a4.501 4.501 0 0 1-2.892 8.03H6.5a3.75 3.75 0 0 1-2-6.928V4.25a.75.75 0 0 1 .75-.75h-.75Z" clip-rule="evenodd" />
            </svg>
          </span>
          Continuer {{ $featuredFormation->title }}
        </a>
        <a
          href="{{ route('eleve.formation.show', [$team, $featuredFormation->id]) }}"
          class="inline-flex items-center gap-2 rounded-full border border-white/15 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:border-white/40 hover:bg-white/5"
        >
          Voir les d&eacute;tails
        </a>
      </div>
      @endif
    </div>

    <div class="relative hidden w-full max-w-xs shrink-0 md:block">
      <div class="relative aspect-[3/4] overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-[0_30px_80px_-35px_rgba(14,165,233,0.65)] backdrop-blur">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/60 via-indigo-500/50 to-blue-600/60"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-6">
          @if($featuredFormation)
          <h2 class="text-xl font-semibold text-white">
            {{ $featuredFormation->title }}
          </h2>
          <p class="mt-2 line-clamp-3 text-sm text-slate-100/80">
            {{ $featuredFormation->description ?? 'Poursuivez votre formation et d&eacute;bloquez le prochain module.' }}
          </p>
          <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/20">
            <div
              class="h-full rounded-full bg-white/80 transition-all duration-500"
              style="width: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%;"
            ></div>
          </div>
          <p class="mt-2 text-xs font-medium uppercase tracking-wider text-white/70">
            Progression&nbsp;: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%
          </p>
          @else
          <div class="space-y-3 text-white/80">
            <h2 class="text-lg font-semibold">Aucune formation en cours</h2>
            <p class="text-sm">S&eacute;lectionnez une formation pour commencer votre parcours et recevoir vos recommandations personnalis&eacute;es.</p>
          </div>
          @endif
        </div>
      </div>
      <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full border border-white/20 bg-white/10 blur-xl"></div>
      <div class="absolute -right-8 top-24 h-20 w-20 rounded-full border border-white/10 bg-gradient-to-br from-blue-500/20 to-purple-500/30 blur-xl"></div>
    </div>
  </div>
</section>
