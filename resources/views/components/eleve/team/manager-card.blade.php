@props(['manager', 'team'])

@php
    $name = trim($manager->name ?? '');
    $parts = $name !== '' ? preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [] : [];
    $initials = collect($parts)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<a href="{{ route('eleve.managers.show', [$team, $manager]) }}"
  class="group relative flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-[0_20px_45px_-30px_rgba(15,23,42,0.45)] transition hover:-translate-y-1 hover:border-slate-300 hover:shadow-[0_30px_60px_-35px_rgba(30,64,175,0.45)]">
  <div class="flex items-start gap-4">
    <div class="relative h-14 w-14 flex-shrink-0">
      <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-sky-50 via-indigo-50 to-violet-50"></div>
      <div class="absolute inset-0 rounded-2xl border border-white/70 bg-white/70 shadow-inner"></div>
      <div class="relative flex h-full w-full items-center justify-center rounded-2xl">
        <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400">
          <defs>
            <linearGradient id="avatarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.5" />
              <stop offset="100%" stop-color="#6366f1" stop-opacity="0.5" />
            </linearGradient>
          </defs>
          <circle cx="60" cy="60" r="58" fill="url(#avatarGradient)" opacity="0.4" />
          <circle cx="60" cy="46" r="20" fill="currentColor" opacity="0.65" />
          <path d="M20 102c7-26 33-34 40-34s33 8 40 34" fill="currentColor" opacity="0.4" />
        </svg>
        @if($initials)
          <span class="absolute text-lg font-semibold text-slate-700">{{ $initials }}</span>
        @endif
      </div>
    </div>

    <div class="flex-1 space-y-1">
      <p class="text-base font-semibold text-slate-900">{{ $manager->name }}</p>
      @if($manager->email)
        <p class="text-sm text-slate-500">{{ $manager->email }}</p>
      @endif
      <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
        Manager de l'&eacute;quipe
      </span>
    </div>

    <div class="text-slate-300 transition group-hover:text-slate-500">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
        stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
        <path d="M9 5l7 7-7 7" />
      </svg>
    </div>
  </div>

  <div class="flex items-center justify-between text-xs text-slate-500">
    <span>Voir la fiche</span>
    <span class="inline-flex items-center gap-1 text-slate-400 group-hover:text-slate-600">
      Profil
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
        stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5">
        <path d="M5 12h14" />
        <path d="M13 5l7 7-7 7" />
      </svg>
    </span>
  </div>
</a>
