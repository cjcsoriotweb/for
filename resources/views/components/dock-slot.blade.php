@php
    // Stocker les slots pour accès JavaScript
    $hasToolbar = isset($toolbar);
    $hasChatia = isset($chatia);
    $hasTutorial = isset($tutorial);
    $hasTutor = isset($tutor);
    $hasSearch = isset($search);
    $hasSupport = isset($bug) || isset($support);
@endphp

<!-- Boutons toolbar -->
@if($hasToolbar)
{{ $toolbar }}
@else
<!-- Boutons par défaut si pas de toolbar personnalisé -->
<div class="flex items-center gap-2">
  <!-- Bouton Assistant IA -->
  @if($hasChatia)
  <button type="button"
          data-dock-target="chatia"
          data-dock-action="show-slot"
          data-dock-slot="chatia"
          class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-slate-900">
      <span data-dock-indicator class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <span data-dock-badge class="absolute -top-1 -right-1 hidden min-w-[1.25rem] rounded-full bg-rose-600 px-1 text-center text-[10px] font-semibold leading-5 text-white shadow"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M7 8h10"></path><path d="M7 12h6"></path><path d="M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z"></path>
      </svg>
  </button>
  @endif

  <!-- Bouton Professeur -->
  @if($hasTutor)
  <button type="button"
          data-dock-target="tutor"
          data-dock-action="show-slot"
          data-dock-slot="tutor"
          class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-amber-500">
      <span data-dock-indicator class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <span data-dock-badge class="absolute -top-1 -right-1 hidden min-w-[1.25rem] rounded-full bg-rose-600 px-1 text-center text-[10px] font-semibold leading-5 text-white shadow"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path><path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path><path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path><path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
      </svg>
  </button>
  @endif

  <!-- Bouton Tutoriels -->
  @if($hasTutorial)
  <button type="button"
          data-dock-target="tutorial"
          data-dock-action="show-slot"
          data-dock-slot="tutorial"
          class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-green-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-green-500">
      <span data-dock-indicator class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <span data-dock-badge class="absolute -top-1 -right-1 hidden min-w-[1.25rem] rounded-full bg-rose-600 px-1 text-center text-[10px] font-semibold leading-5 text-white shadow"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479l-6.16 3.422L5.839 17.057a12.083 12.083 0 01.665-6.479L12 14z"></path><path d="M12 14l0 7"></path>
      </svg>
  </button>
  @endif

  <!-- Bouton Support -->
  @if($hasSupport)
  <button type="button"
          data-dock-target="support"
          data-dock-action="show-slot"
          data-dock-slot="support"
          class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-md hover:-translate-y-1 hover:shadow-lg focus-visible:ring-sky-500">
      <span data-dock-indicator class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <span data-dock-badge class="absolute -top-1 -right-1 hidden min-w-[1.25rem] rounded-full bg-rose-600 px-1 text-center text-[10px] font-semibold leading-5 text-white shadow"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10 12v-1"></path><path d="M14 12v-1"></path><path d="M12 6a5 5 0 0 0-5 5v1a7 7 0 0 0 4 6v1a1 1 0 0 0 2 0v-1a7 7 0 0 0 4-6v-1a5 5 0 0 0-5-5z"></path><path d="M7 10H5"></path><path d="M19 10h-2"></path><path d="M7 16l-2 2"></path><path d="M19 16l2 2"></path><path d="M7 4l1.5 1.5"></path><path d="M17 4 15.5 5.5"></path>
      </svg>
  </button>
  @endif
</div>
@endif

<!-- Contenu des slots (cachés par défaut) -->
<div class="dock-slots hidden" data-slots-available="{{ $hasChatia ? 'chatia ' : '' }}{{ $hasTutorial ? 'tutorial ' : '' }}{{ $hasTutor ? 'tutor ' : '' }}{{ $hasSupport ? 'support ' : '' }}{{ $hasSearch ? 'search ' : '' }}">
  @if($hasChatia)
  <div class="dock-slot-content" data-slot-name="chatia">
    {{ $chatia }}
  </div>
  @endif

  @if($hasTutorial)
  <div class="dock-slot-content" data-slot-name="tutorial">
    {{ $tutorial }}
  </div>
  @endif

  @if($hasTutor)
  <div class="dock-slot-content" data-slot-name="tutor">
    {{ $tutor }}
  </div>
  @endif

  @if($hasSupport)
  <div class="dock-slot-content" data-slot-name="support">
    {{ $support ?? $bug }}
  </div>
  @endif

  @if($hasSearch)
  <div class="dock-slot-content" data-slot-name="search">
    {{ $search }}
  </div>
  @endif
</div>
