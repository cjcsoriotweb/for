<!-- Bouton flottant modernise pour l'assistant IA -->
<div class="pointer-events-none fixed inset-0 z-[9999] flex items-end justify-end p-5 sm:p-6">
  <a href="{{url('test')}}"
    type="button"
    aria-label="Discuter avec l'assistant IA"
    class="assistant-launcher pointer-events-auto group relative flex items-center gap-4 overflow-hidden rounded-full border border-white/15
           bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-5 py-2.5 text-white shadow-2xl shadow-indigo-600/40
           transition-all duration-200 hover:-translate-y-0.5 hover:border-white/25 hover:shadow-pink-500/40 focus:outline-none
           focus-visible:ring-4 focus-visible:ring-pink-200/60"
    data-assistant-trigger>

    <!-- Halo anime -->
    <span aria-hidden="true" class="assistant-launcher__ring"></span>
    <span aria-hidden="true" class="assistant-launcher__beam"></span>
    <span aria-hidden="true" class="assistant-launcher__glow"></span>
    <span aria-hidden="true" class="assistant-launcher__spark"></span>

    <!-- Texte -->
    <span class="flex flex-col text-left leading-tight">
      <span class="text-[10px] font-semibold uppercase tracking-[0.45em] text-white/70">Assistant</span>
      <span class="text-base font-semibold tracking-tight">Parler maintenant</span>
    </span>

    <!-- Pulse decorative -->
    <span aria-hidden="true" class="assistant-launcher__pulse"></span>

  </a>
</div>
