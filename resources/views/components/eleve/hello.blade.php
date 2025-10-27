@php
$formations = collect($currentFormation ?? []);
$nextFormation = $formations->first(fn ($formation) => empty($formation->is_completed));
$featuredFormation = $nextFormation ?? $formations->first();
$inProgressCount = $formations->filter(fn ($formation) => empty($formation->is_completed))->count();
@endphp

<section
  class="relative overflow-hidden rounded-3xl border border-white/15 bg-slate-900/45 shadow-[0_50px_140px_-60px_rgba(59,130,246,0.55)]">
  <div class="absolute inset-0">
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-slate-800/40"></div>
    <img src="{{ $team->profile_photo_url }}" alt="{{ $team->name }}"
      class="h-full w-full object-cover opacity-30 mix-blend-luminosity" />
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.45),transparent_60%)]"></div>
  </div>

  <div
    class="relative z-10 flex flex-col gap-12 px-6 py-14 sm:px-10 lg:flex-row lg:items-end lg:justify-between lg:px-16">
    <div class="max-w-2xl space-y-6">
      <p class="text-sm uppercase tracking-[0.35em] text-slate-200/70">Espace apprenant</p>
      <h1 class="text-3xl font-semibold text-white sm:text-4xl md:text-5xl">
        Bonjour {{ Auth::user()->name }}&nbsp;!
      </h1>
      <p class="text-lg text-slate-200/80 sm:text-xl">
        Pr&ecirc;t &agrave; reprendre l&agrave; o&ugrave; vous vous &ecirc;tes arr&ecirc;t&eacute; ? Choisissez une
        formation et poursuivez votre parcours au rythme qui vous convient.
      </p>

      <div class="flex flex-wrap gap-6 pt-4">
        <div>
          <div class="text-4xl font-semibold text-white">
            {{ $inProgressCount }}
          </div>
          <p class="text-sm uppercase tracking-wide text-slate-300/70">Formations en cours</p>
        </div>
      </div>

      @if($featuredFormation)
      <div class="flex flex-wrap items-center gap-4 pt-6">
        <a href="{{ route('eleve.formation.show', [$team, $featuredFormation->id]) }}"
          class="inline-flex items-center gap-3 rounded-full bg-gradient-to-r from-sky-400 via-indigo-500 to-purple-500 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-slate-50 shadow-lg shadow-sky-500/25 transition hover:scale-[1.02] hover:shadow-xl hover:shadow-sky-500/35">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>

          </span>
          Continuer
        </a>

      </div>
      @endif
    </div>

    <div class="relative hidden w-full max-w-xs shrink-0 md:block">
      <div
        class="relative aspect-[3/4] overflow-hidden rounded-3xl border border-white/10 bg-white/10 shadow-[0_30px_90px_-40px_rgba(14,165,233,0.55)] backdrop-blur">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-400/65 via-indigo-500/55 to-sky-600/60">

          @if(isset($featuredFormation['cover_image_url']))
          <img src="{{ $featuredFormation['cover_image_url'] }}">
          @else
          <img src="{{ asset('images/formation-placeholder.svg') }}" />
          @endif

        </div>
        <div class="absolute inset-0 flex flex-col justify-end p-6">
          @if($featuredFormation)
          <h2 class="text-xl font-semibold text-white">
            {{ $featuredFormation->title }}
          </h2>
          <p class="mt-2 line-clamp-3 text-sm text-slate-100/80">
            {{ $featuredFormation->description ?? 'Poursuivez votre formation et d&eacute;bloquez le prochain module.'
            }}
          </p>
          <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/20">
            <div class="h-full rounded-full bg-white/80 transition-all duration-500"
              style="width: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%;"></div>
          </div>
          <p class="mt-2 text-xs font-medium uppercase tracking-wider text-white/70">
            Progression&nbsp;: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%
          </p>
          @else
          <div class="space-y-3 text-white/80">
            <h2 class="text-lg font-semibold">Aucune formation en cours</h2>
            <p class="text-sm">S&eacute;lectionnez une formation pour commencer votre parcours et recevoir vos
              recommandations personnalis&eacute;es.</p>
          </div>
          @endif
        </div>
      </div>
      <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full border border-white/20 bg-white/10 blur-xl"></div>
      <div
        class="absolute -right-8 top-24 h-20 w-20 rounded-full border border-white/10 bg-gradient-to-br from-blue-500/20 to-purple-500/30 blur-xl">
      </div>
    </div>
  </div>
</section>