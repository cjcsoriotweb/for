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
    DASHBOARD SECTION
    ======================================== --}}
    <x-formateur.formation.formation-dashboard :stats="$stats" />

    {{-- ========================================
    FEATURED FORMATIONS SECTION
    ======================================== --}}
    <x-formateur.formation.formation-featured :formations="$featuredFormations" />

    {{-- ========================================
    ALL FORMATIONS (PAGINATED)
    ======================================== --}}
    <div id="formations-list" class="mt-12">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Toutes les formations</h2>
      </div>

      @if($formations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($formations as $formation)
            <x-formateur.formation.formation-card :formation="$formation" />
          @endforeach
        </div>

        <div class="mt-8">
          {{ $formations->links() }}
        </div>
      @else
        <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50/50 rounded-2xl border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune formation trouvée</h3>
          <p class="text-gray-600">Créez votre première formation pour commencer.</p>
        </div>
      @endif
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

        function hydrate(root = document) {
          initProgressBars(root);
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

      /* Enhanced animations */
      .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
      }

      .fade-in-up.delay-1 { animation-delay: 0.1s; }
      .fade-in-up.delay-2 { animation-delay: 0.2s; }
      .fade-in-up.delay-3 { animation-delay: 0.3s; }
      .fade-in-up.delay-4 { animation-delay: 0.4s; }
      .fade-in-up.delay-5 { animation-delay: 0.5s; }

      @keyframes fadeInUp {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .slide-in-left {
        animation: slideInLeft 0.8s ease-out forwards;
        opacity: 0;
        transform: translateX(-30px);
      }

      @keyframes slideInLeft {
        to {
          opacity: 1;
          transform: translateX(0);
        }
      }

      .bounce-in {
        animation: bounceIn 0.8s ease-out forwards;
        opacity: 0;
        transform: scale(0.3);
      }

      @keyframes bounceIn {
        0% {
          opacity: 0;
          transform: scale(0.3);
        }
        50% {
          opacity: 1;
          transform: scale(1.05);
        }
        70% {
          transform: scale(0.9);
        }
        100% {
          opacity: 1;
          transform: scale(1);
        }
      }

      .float {
        animation: float 6s ease-in-out infinite;
      }

      @keyframes float {
        0%, 100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-10px);
        }
      }

      .pulse-glow {
        animation: pulseGlow 2s ease-in-out infinite alternate;
      }

      @keyframes pulseGlow {
        from {
          box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        to {
          box-shadow: 0 0 30px rgba(59, 130, 246, 0.8);
        }
      }

      /* Hover effects */
      .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .hover-lift:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      }

      /* Loading shimmer effect */
      .shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
      }

      @keyframes shimmer {
        0% {
          background-position: -200% 0;
        }
        100% {
          background-position: 200% 0;
        }
      }

      /* Custom scrollbar */
      ::-webkit-scrollbar {
        width: 8px;
      }

      ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
      }

      ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #6366f1);
        border-radius: 4px;
      }

      ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #4f46e5);
      }
    </style>
  @endpush
@endonce
