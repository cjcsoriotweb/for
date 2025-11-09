<section class="space-y-8">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <div class="flex items-center gap-3 mb-2">
        <div class="h-1 w-6 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full"></div>
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-600/70">Explorer de nouvelles formations</p>
      </div>
      <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">Formations disponibles</h2>
      <p class="mt-2 max-w-2xl text-sm text-slate-600/80 leading-relaxed">
        Inspirez-vous des offres de votre organisme, comparez les programmes et lancez-vous dans de nouveaux modules
        &agrave; tout moment.
      </p>
    </div>
  </div>

  @if($formations->isNotEmpty())
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="grid grid-cols-1 gap-5 px-4 pb-4 sm:px-2 sm:grid-cols-2">
      @foreach($formations as $formation)
      @php
      $isEnrolled = data_get($formation, 'is_enrolled', false);
      $canJoin = data_get($formation, 'can_join', true);
      @endphp
      <article
        class="group flex w-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-colors duration-200 hover:border-slate-300">
        <div class="h-24 w-full overflow-hidden border-b border-slate-200 bg-slate-50 sm:h-28">
          <img src="{{ $formation['cover_image_url'] }}" alt="Image de couverture de {{ $formation['title'] }}"
            class="h-full w-full object-cover" loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';" />
        </div>

        <div class="flex flex-1 flex-col justify-between gap-4 p-4">
          <div class="space-y-4">
            <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-slate-600">
              <span>{{ $formation['status_label'] }}</span>
              <span class="flex items-center gap-1 rounded-full border border-slate-300 px-3 py-1 text-[11px] font-semibold text-slate-700">
                @if(!is_null($formation['usage_remaining']))
                  {{ trans_choice(':count activation restante|:count activations restantes', $formation['usage_remaining'], ['count' => $formation['usage_remaining']]) }}
                @else
                  Activations illimit&eacute;es
                @endif
              </span>
            </div>

            <div class="space-y-3 text-slate-700">
              <h3 class="text-base font-semibold leading-snug text-slate-900 line-clamp-2">
                {{ $formation['title'] }}
              </h3>
              <p class="text-sm text-slate-600/80 line-clamp-3">
                {{ $formation['description'] }}
              </p>
            </div>
          </div>

          <div class="space-y-3 pt-4">
            @if($formation['has_progress'])
            <div>
              <div
                class="flex items-center justify-between text-[11px] font-semibold uppercase tracking-wide text-slate-600">
                <span>Progression</span>
                <span>{{ $formation['progress_percent'] }}%</span>
              </div>
              <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                <div class="h-full rounded-full bg-slate-600 transition-all duration-300"
                  style="width: {{ $formation['progress_percent'] }}%;"></div>
              </div>
            </div>
            @endif

            @if($isEnrolled)
            <a href="{{ $formation['show_route'] }}"
              class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-1.5 text-sm font-semibold text-slate-950 transition hover:bg-white">
              Continuer la formation
              <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"
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
                class="rounded-xl border border-slate-300 bg-slate-100 px-3 py-2 text-center text-xs font-semibold uppercase tracking-wide text-slate-700">
                Plus d'utilisations disponibles
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
  <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-10 py-14 text-center text-slate-600">
    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-slate-200">
      <svg class="h-8 w-8 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-slate-900">Aucune autre formation disponible</h3>
    <p class="mt-3 text-sm text-slate-500">
      Revenez bient&ocirc;t pour d&eacute;couvrir les nouvelles formations propos&eacute;es &agrave; votre &eacute;quipe.
    </p>
  </div>
  @endif
</section>
