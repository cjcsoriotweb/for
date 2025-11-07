@php
$formations = collect($currentFormation ?? []);
$nextFormation = $formations->first(fn ($formation) => empty($formation->is_completed));
$featuredFormation = $nextFormation ?? $formations->first();
$inProgressCount = $formations->filter(fn ($formation) => empty($formation->is_completed))->count();
@endphp

<section
  class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_50px_140px_-60px_rgba(59,130,246,0.15)]">
  <div class="absolute inset-0">
    <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-slate-50/80 to-slate-100/60"></div>
    <img src="{{ $team->profile_photo_url }}" alt="{{ $team->name }}"
      class="h-full w-full object-cover opacity-20 mix-blend-normal" />
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.15),transparent_60%)]"></div>
  </div>

  <!-- Logo dans le coin supérieur droit -->
  <div class="absolute top-6 right-6 z-20">
    <div class="h-16 w-16 rounded-2xl border-2 border-white/20 bg-white/10 backdrop-blur-sm shadow-lg overflow-hidden">
      <img src="{{ asset('logo.png') }}" alt="Logo" class="h-full w-full object-cover" />
    </div>
  </div>

  <div
    class="relative z-10 flex flex-col gap-12 px-6 py-14 sm:px-10 lg:flex-row lg:items-end lg:justify-between lg:px-16">
    <div class="max-w-2xl space-y-6">
      <div class="flex items-center gap-3">
        <div class="h-1 w-8 bg-gradient-to-r from-sky-400 to-indigo-500 rounded-full"></div>
        <p class="text-sm uppercase tracking-[0.35em] text-slate-600/70 font-medium">Espace apprenant</p>
      </div>
      <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl md:text-5xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 bg-clip-text text-transparent">
        Bonjour {{ Auth::user()->name }}&nbsp;!
      </h1>
      <p class="text-lg text-slate-700/90 sm:text-xl leading-relaxed">
        Pr&ecirc;t &agrave; reprendre l&agrave; o&ugrave; vous vous &ecirc;tes arr&ecirc;t&eacute; ? Choisissez une
        formation et poursuivez votre parcours au rythme qui vous convient.
      </p>

      <div class="flex flex-wrap gap-6 pt-4">
        <div>
          <div class="text-4xl font-semibold text-slate-900">
            {{ $inProgressCount }}
          </div>
          <p class="text-sm uppercase tracking-wide text-slate-600/70">Formations en cours</p>
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
          @if($featuredFormation)

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
          <h2 class="text-xl font-semibold text-slate-900">
            {{ $featuredFormation->title }}
          </h2>
          <p class="mt-2 line-clamp-3 text-sm text-slate-700/80">
            {{ $featuredFormation->description ?? 'Poursuivez votre formation et d&eacute;bloquez le prochain module.'
            }}
          </p>
          <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-200/50">
            <div class="h-full rounded-full bg-slate-600 transition-all duration-500"
              style="width: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%;"></div>
          </div>
          <p class="mt-2 text-xs font-medium uppercase tracking-wider text-slate-600/70">
            Progression&nbsp;: {{ (int) ($featuredFormation->progress_data['progress_percent'] ?? 0) }}%
          </p>

        </div>

      </div>
   
    </div>
          @endif

  </div>
</section>
