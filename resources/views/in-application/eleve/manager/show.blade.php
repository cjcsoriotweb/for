<x-eleve-layout :team="$team">
  <div class="min-h-[calc(100vh-8rem)] bg-white text-slate-950">
    <div class="mx-auto max-w-4xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
      <a href="{{ route('eleve.index', $team) }}"
        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
          stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
          <path d="M15 19l-7-7 7-7" />
        </svg>
        Retour &agrave; mes managers
      </a>

      <section class="rounded-3xl border border-slate-200 bg-slate-50/60 p-6 shadow-sm sm:p-10">
        <div class="flex flex-col gap-8 lg:flex-row">
          <div class="flex-1 space-y-6">
            <div class="flex items-center gap-5">
              <div class="relative h-20 w-20 flex-shrink-0 overflow-hidden rounded-2xl border border-white/80 bg-white shadow-lg">
                @if($manager->profile_photo_url)
                  <img src="{{ $manager->profile_photo_url }}" alt="{{ $manager->name }}"
                    class="h-full w-full object-cover" />
                @else
                  <div class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-400">
                    <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12">
                      <circle cx="60" cy="60" r="50" fill="#e0f2fe" />
                      <circle cx="60" cy="48" r="20" fill="#94a3b8" />
                      <path d="M24 102c7-26 33-34 40-34s33 8 40 34" fill="#cbd5f5" />
                    </svg>
                  </div>
                @endif
              </div>
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500/80">Manager</p>
                <h1 class="text-3xl font-bold text-slate-900">{{ $manager->name }}</h1>
                <p class="text-sm text-slate-500">Accompagne {{ $team->name }}</p>
              </div>
            </div>

            <p class="text-base leading-relaxed text-slate-600">
              Ce manager est votre point de contact privili&eacute;gi&eacute; pour l'organisation des parcours et la
              validation de vos formations. N'h&eacute;sitez pas &agrave; lui &eacute;crire si vous avez une question ou
              besoin d'un suivi personnalis&eacute;.
            </p>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="rounded-2xl border border-white/80 bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400">Email</p>
                <p class="mt-1 text-sm font-medium text-slate-900">
                  {{ $manager->email ?? 'Non renseign\u00e9' }}
                </p>
              </div>
              <div class="rounded-2xl border border-white/80 bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-slate-400">R\u00f4le</p>
                <p class="mt-1 text-sm font-medium text-slate-900">Manager de l'&eacute;quipe</p>
                @if($managerTeamPivot?->created_at)
                  <p class="text-xs text-slate-500">
                    Depuis le {{ $managerTeamPivot->created_at->format('d/m/Y') }}
                  </p>
                @endif
              </div>
            </div>

            @if($manager->email)
              <div class="flex flex-wrap gap-3">
                <a href="mailto:{{ $manager->email }}"
                  class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <path d="M4 6h16v12H4z" />
                    <path d="M22 6l-10 7L2 6" />
                  </svg>
                  Contacter par email
                </a>
              </div>
            @endif
          </div>

          <div class="rounded-2xl border border-white/80 bg-white/80 p-5 lg:w-72">
            <h2 class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500/80">Infos &eacute;quipe</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-600">
              <li class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                R&ocirc;le de r&eacute;f&eacute;rent pour les apprenants.
              </li>
              <li class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-indigo-400"></span>
                Disponible pour suivre votre progression.
              </li>
              <li class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-sky-400"></span>
                Peut valider vos demandes de compl&eacute;tion.
              </li>
            </ul>
          </div>
        </div>
      </section>
    </div>
  </div>
</x-eleve-layout>
