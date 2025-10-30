<!-- Conteneur principal du dock -->
<div class="dock-container fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50">
  {{ $slot }}

  <!-- Menu iframe qui s'ouvre au dessus du dock -->
  <div id="dock-menu"
       class="absolute bottom-20 left-1/2 transform -translate-x-1/2 hidden z-[1500]">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-600 overflow-hidden"
         style="width: 600px; height: 500px;">

      <!-- Header avec bouton fermer -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="dock-menu-title">Contenu</h3>
        <button onclick="closeDockMenu()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Contenu iframe -->
      <iframe id="dock-iframe"
              class="w-full border-0"
              style="height: calc(100% - 80px);"
              src="">
      </iframe>
    </div>

    <!-- Flèche pointant vers le dock -->
    <div class="absolute top-full left-1/2 transform -translate-x-1/2">
      <div class="w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-white dark:border-t-gray-800"></div>
      <div class="w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-gray-200 dark:border-t-gray-600 absolute top-0 left-1/2 transform -translate-x-1/2 translate-y-px"></div>
    </div>
  </div>

  <!-- Overlay pour fermer en cliquant dehors -->
  <div id="dock-overlay"
       class="fixed inset-0 bg-black bg-opacity-50 hidden z-[1499]"
       onclick="closeDockMenu()">
  </div>
</div>

<script>
  function openDockMenu(title, url) {
    document.getElementById('dock-menu-title').textContent = title;
    document.getElementById('dock-iframe').src = url;
    document.getElementById('dock-menu').classList.remove('hidden');
    document.getElementById('dock-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Empêcher scroll page
  }

  function closeDockMenu() {
    document.getElementById('dock-menu').classList.add('hidden');
    document.getElementById('dock-overlay').classList.add('hidden');
    document.getElementById('dock-iframe').src = ''; // Reset iframe
    document.body.style.overflow = ''; // Restaurer scroll
  }

  // Fermer avec la touche Échap
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('dock-menu').classList.contains('hidden')) {
      closeDockMenu();
    }
  });

  // Initialiser les gestionnaires d'événements pour les boutons dock
  document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
      const button = event.target.closest('[data-dock-action]');
      if (button) {
        const action = button.getAttribute('data-dock-action');
        if (action === 'show-slot') {
          const slotName = button.getAttribute('data-dock-slot');
          const title = button.getAttribute('data-dock-title');

          // Déterminer l'URL selon le slot
          let url = '';
          switch(slotName) {
            case 'chatia':
              url = '/mon-compte/assistant-chat';
              break;
            case 'tutorial':
              url = '/mon-compte/tutoriels';
              break;
            case 'tutor':
              url = '/mon-compte/professeur';
              break;
            case 'support':
              url = '/mon-compte/support';
              break;
            case 'search':
              url = '/mon-compte/recherche';
              break;
          }

          if (url) {
            openDockMenu(title, url);
          }
        }
      }
    });
  });
</script>
