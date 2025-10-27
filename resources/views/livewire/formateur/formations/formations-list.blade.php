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

  {{-- ========================================
  ENHANCED JAVASCRIPT SECTION
  ======================================== --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Animate progress bars on scroll
      const progressBars = document.querySelectorAll('.progress-bar');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const bar = entry.target;
            const width = bar.getAttribute('data-width');
            setTimeout(() => {
              bar.style.width = width + '%';
            }, 200);
          }
        });
      });

      progressBars.forEach(bar => {
        observer.observe(bar);
      });

      // Enhanced search functionality
      const searchInput = document.querySelector('input[wire\\:model\\.live="search"]');
      if (searchInput) {
        searchInput.addEventListener('focus', function () {
          this.parentElement.classList.add('ring-4', 'ring-blue-500/30');
        });

        searchInput.addEventListener('blur', function () {
          this.parentElement.classList.remove('ring-4', 'ring-blue-500/30');
        });
      }
    });
  </script>

  {{-- ========================================
  CUSTOM STYLES SECTION
  ======================================== --}}
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

    /* Enhanced hover effects */
    .group:hover .group-hover\\:scale-105 {
      transform: scale(1.05) translateY(-4px);
    }

    /* Smooth animations for all interactive elements */
    * {
      scroll-behavior: smooth;
    }

    /* Custom gradient text animation */
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
</div>