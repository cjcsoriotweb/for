{{-- ========================================
FORMATIONS LIST - MAIN CONTAINER
======================================== --}}
<div class="min-h-screen ">



  {{-- Main Content Container --}}
  <div class="relative">

    {{-- ========================================
    HEADER SECTION
    ======================================== --}}
    <x-formateur.formation.formation-header />

    {{-- ========================================
    SEARCH SECTION
    ======================================== --}}
    <x-formateur.formation.formation-search :search="$search" :formations="$formations" />

    {{-- ========================================
    FORMATIONS LIST SECTION
    ======================================== --}}
    <div class="space-y-8">
      @forelse ($formations as $formation)
      <x-formateur.formation.formation-card :formation="$formation" />
      @empty
      <x-formateur.formation.formation-empty-state />
      @endforelse

      {{-- ========================================
      PAGINATION SECTION
      ======================================== --}}
      <x-formateur.formation.formation-pagination :formations="$formations" />
    </div>
  </div>

</div>

@once
  @push('scripts')
    <script>
      (function () {
        let progressObserver = null;

        function ensureProgressObserver() {
          if (progressObserver) {
            return progressObserver;
          }

          progressObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
              if (!entry.isIntersecting) {
                return;
              }

              const bar = entry.target;
              const targetWidth = bar.getAttribute('data-width');

              if (!bar.dataset.progressAnimated) {
                bar.dataset.progressAnimated = 'true';
                requestAnimationFrame(() => {
                  bar.style.width = `${targetWidth}%`;
                });
              }

              observer.unobserve(bar);
            });
          });

          return progressObserver;
        }

        function initProgressBars(root = document) {
          const observer = ensureProgressObserver();
          root.querySelectorAll('.progress-bar[data-width]').forEach((bar) => {
            if (bar.dataset.progressObserved) {
              return;
            }

            bar.dataset.progressObserved = 'true';
            observer.observe(bar);
          });
        }

        function initSearchInput(root = document) {
          const searchInput = root.querySelector('input[wire\\:model\\.live="search"]');

          if (!searchInput || searchInput.dataset.formationsSearchBound) {
            return;
          }

          searchInput.dataset.formationsSearchBound = 'true';

          searchInput.addEventListener('focus', function () {
            this.parentElement?.classList.add('ring-4', 'ring-blue-500/30');
          });

          searchInput.addEventListener('blur', function () {
            this.parentElement?.classList.remove('ring-4', 'ring-blue-500/30');
          });
        }

        function hydrate(root = document) {
          initProgressBars(root);
          initSearchInput(root);
        }

        function boot() {
          hydrate(document);
        }

        document.addEventListener('DOMContentLoaded', boot, { once: true });

        document.addEventListener('livewire:load', function () {
          boot();

          if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('message.processed', function (_message, component) {
              hydrate(component ? component.el : document);
            });
          }
        });
      })();
    </script>
  @endpush
@endonce

@once
  @push('styles')
    <style>
      .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .progress-bar {
        width: 0%;
        transition: width 1s ease-out;
      }

      .group:hover .group-hover\:scale-105 {
        transform: scale(1.05) translateY(-4px);
      }

      * {
        scroll-behavior: smooth;
      }

      .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
      }

      @keyframes gradient {
        0% {
          background-position: 0% 50%;
        }

        50% {
          background-position: 100% 50%;
        }

        100% {
          background-position: 0% 50%;
        }
      }
    </style>
  @endpush
@endonce
