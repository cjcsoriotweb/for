<div class="dock-container fixed bottom-6 left-1/2 -translate-x-1/2 transform z-[1200]" data-dock-root>
  {{ $slot }}

  <div class="hidden" data-dock-modal>
    <div class="fixed inset-0 z-[1190] bg-slate-950/60 backdrop-blur-sm" data-dock-overlay></div>

    <div class="fixed inset-0 z-[1191] flex flex-col pointer-events-none md:items-center md:justify-center">
      <div class="pointer-events-auto mt-auto w-full px-3 pb-4 md:mt-0 md:w-auto md:px-0 md:pb-0">
        <div
          class="relative w-full rounded-3xl border border-slate-200/70 bg-white/95 text-slate-900 shadow-2xl dark:border-slate-700/60 dark:bg-slate-900/95 dark:text-slate-100 md:w-[820px] max-h-[90vh] md:max-h-[85vh] min-h-[60vh] flex flex-col"
          data-dock-panel
        >
          <button
            type="button"
            data-dock-close
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/70 text-slate-500 transition hover:bg-white hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500 dark:bg-slate-800/70 dark:text-slate-300 dark:hover:bg-slate-700"
            aria-label="{{ __('Fermer le dock') }}"
          >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18 18 6"></path>
            </svg>
          </button>

          <div class="flex-1 overflow-hidden">
            <div data-dock-content class="h-full overflow-y-auto">
              <!-- Slot content injected via script -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  function initializeDock(root) {
    if (!root || root.dataset.dockReady === '1') {
      return;
    }
    root.dataset.dockReady = '1';

    const modal = root.querySelector('[data-dock-modal]');
    const overlay = modal?.querySelector('[data-dock-overlay]');
    const closeButtons = modal ? Array.from(modal.querySelectorAll('[data-dock-close]')) : [];
    const modalContent = modal?.querySelector('[data-dock-content]');
    const slotNodes = Array.from(root.querySelectorAll('.dock-slot-content'));
    const slots = {};

    if (!modal || !modalContent) {
      return;
    }

    slotNodes.forEach((node) => {
      const name = node.dataset.slotName;
      if (!name) {
        return;
      }
      slots[name] = node;
      node.classList.add('hidden');
      modalContent.appendChild(node);
    });

    const slotsWrapper = root.querySelector('.dock-slots');
    if (slotsWrapper) {
      slotsWrapper.remove();
    }

    const lockScroll = () => {
      document.documentElement.classList.add('overflow-hidden');
      document.body.classList.add('overflow-hidden');
    };

    const unlockScroll = () => {
      document.documentElement.classList.remove('overflow-hidden');
      document.body.classList.remove('overflow-hidden');
    };

    const closeModal = () => {
      if (modal.classList.contains('hidden')) {
        return;
      }
      modal.classList.add('hidden');
      unlockScroll();
      window.dispatchEvent(new CustomEvent('dock:slot-closed'));
    };

    const openSlot = (slotName, trigger) => {
      const target = slots[slotName];
      if (!target) {
        return;
      }

      Object.values(slots).forEach((node) => node.classList.add('hidden'));
      target.classList.remove('hidden');

      modal.classList.remove('hidden');
      modalContent.scrollTop = 0;
      lockScroll();
      window.dispatchEvent(new CustomEvent('dock:slot-opened', {
        detail: { slot: slotName, trigger: trigger || null },
      }));
    };

    if (overlay) {
      overlay.addEventListener('click', closeModal);
    }

    closeButtons.forEach((button) => {
      button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeModal();
      }
    });

    root.addEventListener('click', (event) => {
      const button = event.target.closest('[data-dock-action="show-slot"]');
      if (!button) {
        return;
      }

      const slotName = button.getAttribute('data-dock-slot');
      if (!slotName) {
        return;
      }

      event.preventDefault();
      openSlot(slotName, button);
    });
  }

  function bootstrapDock() {
    document
      .querySelectorAll('[data-dock-root]')
      .forEach((root) => initializeDock(root));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootstrapDock);
  } else {
    bootstrapDock();
  }
})();
</script>
