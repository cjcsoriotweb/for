<section class="space-y-6">


  @if($formations->isNotEmpty())
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="flex snap-x snap-mandatory gap-6 overflow-x-auto px-4 pb-6 sm:px-2">
      @foreach($formations as $formation)
      @php
      $isEnrolled = data_get($formation, 'is_enrolled', false);
      $canJoin = data_get($formation, 'can_join', true);
      @endphp
      <article
        class="group relative isolate min-w-[260px] max-w-xs snap-start overflow-hidden rounded-3xl border border-white/15 bg-slate-900/55 transition duration-300 hover:-translate-y-1">
        <div
          class="absolute inset-0 bg-gradient-to-br from-sky-500/80 via-indigo-500/60 to-purple-500/50 opacity-60 transition group-hover:opacity-80">
        </div>
        <div
          class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.42),transparent_60%)] mix-blend-screen opacity-35">
        </div>

        <div class="relative z-10 h-36 w-full overflow-hidden border-b border-white/15 bg-white/10 sm:h-40">
          <img src="{{ $formation['cover_image_url'] }}" alt="Image de couverture de {{ $formation['title'] }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
        </div>

        <div class="relative z-10 flex flex-col justify-between gap-6 p-6">
          <div class="space-y-4">
            <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-white/70">
              <span>{{ $formation['status_label'] }}</span>
              <span class=" flex rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold text-white">
                {{ $formation['price_label'] }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
              </span>
            </div>

            <div class="space-y-3">
              <h3 class="text-lg font-semibold leading-snug text-white line-clamp-2">
                {{ $formation['title'] }}
              </h3>
              <p class="text-sm text-slate-100/80 line-clamp-3">
                {{ $formation['description'] }}
              </p>
            </div>
          </div>

          <div class="space-y-4 pt-6">
            @if($formation['has_progress'])
            <div>
              <div
                class="flex items-center justify-between text-[11px] font-semibold uppercase tracking-wide text-white/65">
                <span>Progression</span>
                <span>{{ $formation['progress_percent'] }}%</span>
              </div>
              <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/20">
                <div class="h-full rounded-full bg-white/80 transition-all duration-500"
                  style="width: {{ $formation['progress_percent'] }}%;"></div>
              </div>
            </div>
            @endif

            @if($isEnrolled)
            <a href="{{ $formation['show_route'] }}"
              class="group/btn inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30">
              Continuer la formation
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
            @else
            @if($canJoin)
            <x-forms.eleve.join-formation :formation="$formation" />
            @else
            <div
              class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-white/75">
              Solde application insuffisant
            </div>
            @endif
            @endif
          </div>
        </div>
      </article>
      @endforeach
    </div>
  </div>
  @else
  <div class="rounded-3xl border border-dashed border-white/20 bg-gradient-to-br from-slate-800/80 via-indigo-900/60 to-purple-900/50 px-10 py-16 text-center shadow-xl transition hover:shadow-2xl">
    <div class="mx-auto mb-7 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-tr from-sky-500/30 via-indigo-500/20 to-purple-500/30 shadow-inner">
      <svg class="h-12 w-12 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
      </svg>
    </div>
    <h3 class="text-2xl font-bold text-white drop-shadow">Aucune autre formation disponible</h3>
    <p class="mt-4 text-base text-slate-200/90">
      Revenez bientôt pour découvrir les nouvelles formations proposées à votre équipe.
    </p>
  </div>
  @endif
</section>
