<!-- Bouton flottant modernise pour l'assistant IA -->
<div class="pointer-events-none fixed inset-0 z-[9999] flex items-end justify-end p-5 sm:p-6">
  <a
    href="{{url('/test')}}"
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


<style>
  .assistant-launcher {
    opacity: 1;
    animation: idle-fade 1s ease forwards;
    animation-delay: 5s;
    animation-fill-mode: forwards;
  }

  .assistant-launcher__ring {
    position: absolute;
    inset: -45%;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.35), transparent 65%);
    opacity: 0.4;
    animation: pulse-ring 4s ease-in-out infinite;
    pointer-events: none;
  }

  .assistant-launcher__beam {
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, rgba(255, 255, 255, 0) 45%, rgba(255, 255, 255, 0.4) 55%, rgba(255, 255, 255, 0) 65%);
    transform: translateX(-120%);
    animation: shimmer 6s linear infinite;
    pointer-events: none;
  }

  .assistant-launcher__glow {
    position: absolute;
    inset: -30%;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, transparent 60%);
    filter: blur(12px);
    opacity: 0.35;
    pointer-events: none;
  }

  .assistant-launcher__spark {
    position: absolute;
    width: 140%;
    height: 140%;
    left: -10%;
    top: -40%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    opacity: 0.25;
    transform: rotate(6deg);
    animation: spark-drift 5s ease-in-out infinite;
    pointer-events: none;
  }

  .assistant-launcher__pulse {
    display: inline-flex;
    width: 12px;
    height: 12px;
    border-radius: 999px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    position: relative;
  }

  .assistant-launcher__pulse::after {
    content: "";
    position: absolute;
    inset: 2px;
    border-radius: inherit;
    background: #aef5c5;
    box-shadow: 0 0 12px rgba(174, 245, 197, 0.85);
    animation: pulse-dot 2.4s ease-in-out infinite;
  }

  .assistant-launcher:hover,
  .assistant-launcher:focus-visible {
    opacity: 1;
    animation: none;
  }

  @keyframes pulse-ring {
    0%, 100% { opacity: 0.15; transform: scale(0.85); }
    50% { opacity: 0.4; transform: scale(1); }
  }

  @keyframes shimmer {
    0% { transform: translateX(-120%); }
    100% { transform: translateX(120%); }
  }

  @keyframes spark-drift {
    0%, 100% { transform: translateX(-10%) rotate(6deg); opacity: 0.2; }
    50% { transform: translateX(10%) rotate(6deg); opacity: 0.35; }
  }

  @keyframes pulse-dot {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(0.65); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
  }

  @keyframes idle-fade {
    0% { opacity: 1; }
    100% { opacity: 0.12; }
  }
</style>

<script>
  (() => {
    const initAssistantModal = () => {
      const trigger = document.querySelector('[data-assistant-trigger]');
      const modal = document.getElementById('assistant-modal');
      const closeBtn = modal?.querySelector('[data-assistant-close]');

      if (!trigger || !modal || modal.dataset.initialized === 'true') return;

      const openModal = () => {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        modal.focus?.();
      };

      const closeModal = () => {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
      };

      trigger.addEventListener('click', openModal);
      closeBtn?.addEventListener('click', closeModal);

      modal.addEventListener('click', (event) => {
        if (event.target === modal) {
          closeModal();
        }
      });

      window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
          closeModal();
        }
      });

      modal.dataset.initialized = 'true';
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initAssistantModal, { once: true });
    } else {
      initAssistantModal();
    }
  })();
</script>
