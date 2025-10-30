<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name') . ' · Tutoriel')</title>
    <link rel="stylesheet" href="https://unpkg.com/fullpage.js/dist/fullpage.min.css">
    @stack('tutorial-styles')
    <x-meta-header/>
    <style>
        /* Enhanced background styling */
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #0f172a 50%, #020617 100%);
            background-attachment: fixed;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 25px 25px, rgba(59, 130, 246, 0.05) 2%, transparent 2%),
                radial-gradient(circle at 75px 75px, rgba(139, 92, 246, 0.03) 2%, transparent 2%);
            background-size: 100px 100px;
            pointer-events: none;
            z-index: -1;
        }

        /* Custom FullPage.js navigation styling */
        #fp-nav.fp-right {
            right: 24px;
        }

        #fp-nav ul li a span {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #fp-nav ul li a.active span {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-color: rgba(59, 130, 246, 0.5);
            transform: scale(1.2);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        #fp-nav ul li:hover a span {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        #fp-nav ul li a.active:hover span {
            transform: scale(1.3);
        }

        /* Smooth section transitions */
        .fp-section {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Enhanced scroll overflow styling */
        .fp-overflow .fp-scroller {
            scrollbar-width: thin;
            scrollbar-color: rgba(71, 85, 105, 0.3) rgba(15, 23, 42, 0.1);
        }

        .fp-overflow .fp-scroller::-webkit-scrollbar {
            width: 6px;
        }

        .fp-overflow .fp-scroller::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.1);
            border-radius: 3px;
        }

        .fp-overflow .fp-scroller::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.3);
            border-radius: 3px;
            transition: background 0.2s ease;
        }

        .fp-overflow .fp-scroller::-webkit-scrollbar-thumb:hover {
            background: rgba(100, 116, 139, 0.5);
        }

        /* Responsive navigation adjustments */
        @media (max-width: 768px) {
            #fp-nav.fp-right {
                right: 16px;
            }

            #fp-nav ul li a span {
                width: 10px;
                height: 10px;
            }
        }

        /* Fade in animation for content */
        .fp-section .section-content {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <header class="fixed inset-x-0 top-0 z-50 bg-gradient-to-r from-slate-900/90 via-slate-800/90 to-slate-900/90 backdrop-blur-lg border-b border-slate-700/50 shadow-2xl">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-white to-slate-200 bg-clip-text text-transparent">
                        @yield('header-title', __('Tutoriel'))
                    </h1>
                    <p class="text-sm text-slate-300 mt-0.5">
                        @yield('header-subtitle')
                    </p>
                </div>
            </div>
            @if (isset($tutorialKey) && !($forced ?? false))
                <form method="POST" action="{{ route('tutorial.skip', $tutorialKey) }}">
                    @csrf
                    <input type="hidden" name="return" value="{{ $returnUrl ?? request()->query('return') }}">
                    <button type="submit" class="group relative overflow-hidden rounded-xl border border-slate-600/50 bg-slate-800/50 backdrop-blur px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition-all duration-300 hover:bg-slate-700/60 hover:border-slate-500/50 hover:shadow-xl hover:shadow-slate-900/25">
                        <span class="relative z-10 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            <span>{{ __('Passer le tutoriel') }}</span>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </button>
                </form>
            @elseif (($forced ?? false))
                <div class="flex items-center space-x-3">
                    <div class="animate-pulse">
                        <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                    </div>
                    <span class="rounded-full border border-amber-300/30 bg-gradient-to-r from-amber-500/10 to-orange-500/10 px-4 py-2 text-xs font-bold uppercase tracking-wider text-amber-200 backdrop-blur shadow-lg ring-1 ring-amber-400/20">
                        <svg class="w-3 h-3 inline mr-1.5 -mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                        </svg>
                        {{ __('Lecture obligatoire') }}
                    </span>
                </div>
            @endif
        </div>
    </header>

    <main id="fullpage" class="pt-24">
        @yield('content')
    </main>

    <script src="https://unpkg.com/fullpage.js/dist/fullpage.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('fullpage');

            if (!container) {
                return;
            }

            if (typeof fullpage_api !== 'undefined') {
                fullpage_api.destroy('all');
            }

            // Enhanced FullPage.js initialization
            new fullpage('#fullpage', {
                licenseKey: 'gplv3-license',
                navigation: true,
                navigationPosition: 'right',
                navigationTooltips: [],
                showActiveTooltip: false,
                slidesNavigation: false,
                scrollOverflow: true,
                scrollOverflowReset: false,
                normalScrollElements: '.scrollable-content',
                credits: {
                    enabled: false,
                },
                easing: 'easeInOutCubic',
                easingcss3: 'cubic-bezier(0.645, 0.045, 0.355, 1)',
                scrollingSpeed: 800,
                autoScrolling: true,
                fitToSection: true,
                fitToSectionDelay: 1000,
                loopTop: false,
                loopBottom: false,
                continuousVertical: false,
                touchSensitivity: 15,
                bigSectionsDestination: null,

                // Enhanced responsiveness
                responsiveWidth: 768,
                responsiveHeight: 600,

                // Callbacks for animations
                onLeave: function(origin, destination, direction) {
                    // Add section transition effects
                    const sections = document.querySelectorAll('.fp-section');
                    sections.forEach(section => {
                        section.classList.remove('active-section');
                    });
                    destination.item.classList.add('active-section');

                    // Add smooth content fading
                    const destinationContent = destination.item.querySelector('.section-content');
                    if (destinationContent) {
                        destinationContent.style.opacity = '0';
                        destinationContent.style.transform = 'translateY(20px)';

                        setTimeout(() => {
                            destinationContent.style.transition = 'all 0.6s ease-out';
                            destinationContent.style.opacity = '1';
                            destinationContent.style.transform = 'translateY(0)';
                        }, 100);
                    }
                },

                afterLoad: function(origin, destination, direction) {
                    // Update navigation active state
                    updateNavigationIndicator(destination.index);
                },

                afterRender: function() {
                    // Initialize section content animations
                    const sections = document.querySelectorAll('.fp-section');
                    sections.forEach((section, index) => {
                        if (index === 0) {
                            section.classList.add('active-section');
                        }

                        const content = section.querySelector('.section-content');
                        if (content && !content.classList.contains('animated')) {
                            content.style.opacity = '0';
                            content.style.transform = 'translateY(20px)';
                            content.classList.add('animated');
                        }
                    });

                    // Setup navigation enhancements
                    setupNavigationEnhancements();
                }
            });

            // Function to setup navigation enhancements
            function setupNavigationEnhancements() {
                const navDots = document.querySelectorAll('#fp-nav ul li a');

                navDots.forEach((dot, index) => {
                    // Add tooltip on hover
                    dot.addEventListener('mouseenter', function() {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'nav-tooltip';
                        tooltip.textContent = `Section ${index + 1}`;
                        tooltip.style.cssText = `
                            position: absolute;
                            right: 50px;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0, 0, 0, 0.8);
                            color: white;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                            white-space: nowrap;
                            opacity: 0;
                            transition: opacity 0.2s ease;
                            pointer-events: none;
                            z-index: 1000;
                        `;
                        document.body.appendChild(tooltip);

                        requestAnimationFrame(() => {
                            tooltip.style.opacity = '1';
                        });

                        dot.addEventListener('mouseleave', function() {
                            tooltip.style.opacity = '0';
                            setTimeout(() => tooltip.remove(), 200);
                        }, { once: true });
                    });
                });
            }

            // Function to update navigation indicator
            function updateNavigationIndicator(activeIndex) {
                const navItems = document.querySelectorAll('#fp-nav ul li');
                navItems.forEach((item, index) => {
                    if (index === activeIndex) {
                        item.classList.add('active-nav-item');
                    } else {
                        item.classList.remove('active-nav-item');
                    }
                });
            }

            // Add smooth scroll behavior for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    if (targetId && typeof fullpage_api !== 'undefined') {
                        fullpage_api.moveTo(targetId);
                    }
                });
            });

            // Add keyboard navigation improvements
            document.addEventListener('keydown', function(e) {
                // Allow escape key to reset position
                if (e.key === 'Escape' && typeof fullpage_api !== 'undefined') {
                    fullpage_api.moveTo(1);
                }

                // Space bar scrolling (with Shift for reverse)
                if (e.key === ' ' && typeof fullpage_api !== 'undefined') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        fullpage_api.moveSectionUp();
                    } else {
                        fullpage_api.moveSectionDown();
                    }
                }
            });

            // Add mobile touch improvements
            let touchStartY = 0;
            document.addEventListener('touchstart', function(e) {
                touchStartY = e.touches[0].clientY;
            });

            document.addEventListener('touchmove', function(e) {
                if (!touchStartY) return;

                const touchEndY = e.touches[0].clientY;
                const diff = touchStartY - touchEndY;

                // Prevent overscroll on mobile
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight && diff > 0) {
                    e.preventDefault();
                } else if (window.scrollY === 0 && diff < 0) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @php
        $hasAssistantNotifications = auth()->check() && auth()->user()->unreadNotifications()->exists();
    @endphp
    <x-ui.layout.assistant-dock :notifications="['assistant' => $hasAssistantNotifications]" />
    @stack('tutorial-scripts')
    @stack('scripts')
</body>
</html>
