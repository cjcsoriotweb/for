<!-- Conteneur principal du dock -->
<div class="dock-container fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50">
  {{ $slot }}

  <!-- Menu iframe qui s'ouvre au dessus du dock -->
  <div id="dock-menu"
       class="absolute bottom-20 left-1/2 transform -translate-x-1/2 hidden z-[1500]">
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-600 overflow-hidden"
         style="width: min(90vw, 820px); height: min(85vh, 720px); max-height: calc(100vh - 140px);">
      <button onclick="closeDockMenu()"
              class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-500">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Contenu iframe -->
      <iframe id="dock-iframe"
              class="h-full w-full border-0"
              src="">
      </iframe>
    </div>

    <!-- Fleche pointant vers le dock -->
    <div class="absolute top-full left-1/2 transform -translate-x-1/2">
      <div class="w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-white dark:border-t-gray-800"></div>
      <div class="w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-gray-200 dark:border-t-gray-600 absolute top-0 left-1/2 transform -translate-x-1/2 translate-y-px"></div>
    </div>
  </div>

  <!-- Overlay pour fermer en cliquant dehors -->
  <div id="dock-overlay"
       class="fixed inset-0 bg-transparent hidden z-[1499]"
       onclick="closeDockMenu()">
  </div>
</div>

<script>
  function openDockMenu(url) {
    const iframe = document.getElementById('dock-iframe');
    if (!iframe || !url) {
      return;
    }

    iframe.src = url;
    document.getElementById('dock-menu').classList.remove('hidden');
    document.getElementById('dock-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Empecher le scroll de la page
  }

  function closeDockMenu() {
    const iframe = document.getElementById('dock-iframe');
    if (iframe) {
      iframe.src = ''; // Reset iframe
    }

    document.getElementById('dock-menu').classList.add('hidden');
    document.getElementById('dock-overlay').classList.add('hidden');
    document.body.style.overflow = ''; // Restaurer le scroll de la page
  }

  // Fermer avec la touche Echap
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('dock-menu').classList.contains('hidden')) {
      closeDockMenu();
    }
  });

  // Initialiser les gestionnaires d'evenements pour les boutons du dock
  document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
      const button = event.target.closest('[data-dock-action]');
      if (button) {
        const action = button.getAttribute('data-dock-action');
        if (action === 'show-slot') {
          const slotName = button.getAttribute('data-dock-slot');

          // Determiner l'URL selon le slot
          let url = '';
          switch (slotName) {
            case 'chatia':
              const originUrl = window.location.href;
              const originLabel = document.title || 'Page courante';
              url = '/mon-compte/assistant-chat?origin=' + encodeURIComponent(originUrl) + '&origin_label=' + encodeURIComponent(originLabel);
              break;
            case 'tutorial':
              url = '/mon-compte/tutoriels';
              break;
            case 'tutor':
              url = '/mon-compte/professeur';
              break;
            case 'support':
              const currentLocation = window.location.href;
              url = '/mon-compte/support?origin=' + encodeURIComponent(currentLocation) + '&origin_label=Dock%20Signaler%20un%20bug';
              break;
            case 'search':
              url = '/mon-compte/recherche';
              break;
          }

          if (url) {
            openDockMenu(url);
          }
        }
      }
    });
  });
</script>
