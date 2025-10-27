<section class="space-y-6">
  <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400/70">Catalogue</p>
      <h2 class="text-2xl font-semibold text-white sm:text-3xl">Vos formations disponibles</h2>
      <p class="mt-2 text-sm text-slate-300/80">
        D&eacute;couvrez de nouveaux parcours et d&eacute;veloppez vos comp&eacute;tences &eacute;tape par &eacute;tape.
      </p>
    </div>
  </div>

  @if($availableFormations->count() > 0)
  <div class="-mx-4 overflow-hidden sm:-mx-2">
    <div class="flex snap-x snap-mandatory gap-6 overflow-x-auto px-4 pb-6 sm:px-2">
      @foreach($availableFormations as $formation)
      @php
          $studentFormationService = app(\App\Services\Formation\StudentFormationService::class);
          $enrollmentService = app(\App\Services\FormationEnrollmentService::class);
          $isEnrolled = $studentFormationService->isEnrolledInFormation(auth()->user(), $formation, $team ?? null);
          $progress = $studentFormationService->getStudentProgress(auth()->user(), $formation);
          $canAfford = isset($team) ? $enrollmentService->canTeamAffordFormation($team, $formation) : false;
          $formattedPrice = $formation->money_amount
              ? number_format((int) $formation->money_amount, 0, ',', ' ') . ' &euro;'
              : 'Gratuit';
          $progressPercent = (int) ($progress['progress_percent'] ?? 0);
      @endphp

      <article class="group relative isolate min-w-[260px] max-w-xs snap-start overflow-hidden rounded-3xl border border-white/15 bg-slate-900/55 transition duration-300 hover:-translate-y-1 hover:border-white/35 hover:shadow-[0_35px_120px_-45px_rgba(14,165,233,0.55)]">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-500/80 via-indigo-500/60 to-purple-500/50 opacity-60 transition group-hover:opacity-80"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.42),transparent_60%)] mix-blend-screen opacity-35"></div>

        <div class="relative flex h-full flex-col justify-between p-6">
          <div class="space-y-4">
            <div class="flex items-center justify-between text-xs font-medium uppercase tracking-wide text-white/70">
              <span>{{ $isEnrolled ? 'D&eacute;j&agrave; inscrit' : 'Nouvelle formation' }}</span>
              <span class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold text-white">{{ $formattedPrice }}</span>
            </div>

            <div class="space-y-3">
              <h3 class="text-lg font-semibold leading-snug text-white line-clamp-2">
                {{ $formation->title }}
              </h3>
              <p class="text-sm text-slate-100/80 line-clamp-3">
                {{ $formation->description ?? 'Explorez ce programme et progressez &agrave; votre rythme.' }}
              </p>
            </div>
          </div>

          <div class="space-y-4 pt-6">
            @if($isEnrolled && $progress)
            <div>
              <div class="flex items-center justify-between text-[11px] font-semibold uppercase tracking-wide text-white/65">
                <span>Progression</span>
                <span>{{ $progressPercent }}%</span>
              </div>
              <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-white/20">
                <div class="h-full rounded-full bg-white/80 transition-all duration-500" style="width: {{ $progressPercent }}%;"></div>
              </div>
            </div>
            @endif

            @if($isEnrolled)
            <a
              href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
              class="group/btn inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/30"
            >
              Continuer la formation
              <svg class="h-4 w-4 transition group-hover/btn:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10.22 4.22a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 0 1-1.06-1.06L13.94 10 10.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.75 10a.75.75 0 0 1 .75-.75h9.5a.75.75 0 0 1 0 1.5h-9.5A.75.75 0 0 1 4.75 10Z" clip-rule="evenodd" />
              </svg>
            </a>
            @else
              @if($canAfford)
              <form
                method="POST"
                action="{{ route('eleve.formation.enroll', ['team' => $team, 'formation' => $formation->id]) }}"
                class="w-full"
              >
                @csrf
                <button
                  type="submit"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:scale-[1.01] hover:shadow-indigo-500/45"
                >
                  S'inscrire &agrave; cette formation
                </button>
              </form>
              @else
              <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-white/75">
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
  <div class="rounded-3xl border border-dashed border-white/15 bg-white/5 px-8 py-14 text-center text-slate-200/80">
    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-white/10">
      <svg class="h-10 w-10 text-white/70" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
      </svg>
    </div>
    <h3 class="text-xl font-semibold text-white">Aucune formation disponible</h3>
    <p class="mt-3 text-sm text-slate-300/80">
      Revenez bient&ocirc;t pour d&eacute;couvrir les nouvelles formations propos&eacute;es &agrave; votre &eacute;quipe.
    </p>
  </div>
  @endif
</section>
