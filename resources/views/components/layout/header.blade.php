<header
    class="sticky top-0 z-40 w-full border-b border-slate-200 bg-white/80 backdrop-blur-md dark:border-slate-700 dark:bg-slate-900/80"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo and Title -->
            <div class="flex items-center space-x-3">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600"
                >
                    <span class="material-symbols-outlined text-lg text-white"
                        >school</span
                    >
                </div>
                <div>
                    <h1
                        class="text-lg font-semibold text-slate-900 dark:text-white"
                    >
                        {{ __("Formation") }}
                    </h1>
                    <a
                        href="{{ route('user.dashboard') }}"
                        class="text-xs text-slate-500 dark:text-slate-400"
                        >{{ __("Espace personnel") }}</a
                    >
                </div>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- User Info -->
                <div class="hidden sm:block text-right">
                    <p
                        class="text-sm font-medium text-slate-900 dark:text-white"
                    >
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <!-- Account Actions Dropdown -->
                <div class="relative" id="user-menu">
                    <button
                        onclick="toggleUserMenu()"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
                    >
                        <span class="material-symbols-outlined text-lg"
                            >person</span
                        >
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        id="user-menu-dropdown"
                        class="absolute right-0 mt-2 w-56 origin-top-right scale-95 transform rounded-lg bg-white py-2 shadow-lg ring-1 ring-slate-200 opacity-0 transition-all focus:outline-none dark:bg-slate-800 dark:ring-slate-700 hidden"
                    >
                        <!-- User Info Header -->
                        <div
                            class="px-4 py-3 border-b border-slate-200 dark:border-slate-700"
                        >
                            <p
                                class="text-sm font-medium text-slate-900 dark:text-white"
                            >
                                {{ auth()->user()->name }}
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
      @if(Auth::user()->superadmin)

                            <a
                                href="{{ route('superadmin.overview') }}"
                                class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"
                            >
                                <span
                                    class="material-symbols-outlined text-base mr-3"
                                    >manage_accounts</span
                                >
                                {{ __("Superadmin") }}
                            </a>
                            @endif
                            <a
                                href="{{ route('profile.show') }}#settings"
                                class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700"
                            >
                                <span
                                    class="material-symbols-outlined text-base mr-3"
                                    >settings</span
                                >
                                {{ __("Paramètres") }}
                            </a>
                        </div>

                        <!-- Logout -->
                        <div
                            class="border-t border-slate-200 pt-1 dark:border-slate-700"
                        >
                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                class="inline"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                >
                                    <span
                                        class="material-symbols-outlined text-base mr-3"
                                        >logout</span
                                    >
                                    {{ __("Déconnexion") }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- JavaScript for enhanced interactions -->
<script>
    // Toggle info section
    function toggleInfo() {
        const info = document.getElementById("info");
        if (info) {
            info.classList.toggle("hidden");
        }
    }

    // Auto-refresh functionality
    let refreshTimeout;

    function startAutoRefresh() {
        refreshTimeout = setTimeout(function () {
            const refreshForm = document.getElementById("refresh-form");
            const refreshIcon = document.getElementById("refresh-icon");

            if (refreshForm) {
                refreshForm.classList.remove("hidden");
            }
            if (refreshIcon) {
                refreshIcon.classList.add("hidden");
            }
        }, 5000);
    }

    // Copy email to clipboard functionality
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard
                .writeText(text)
                .then(function () {
                    // Show success feedback
                    showToast("Adresse e-mail copiée !");
                })
                .catch(function (err) {
                    console.error("Erreur lors de la copie: ", err);
                    fallbackCopyTextToClipboard(text);
                });
        } else {
            fallbackCopyTextToClipboard(text);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand("copy");
            if (successful) {
                showToast("Adresse e-mail copiée !");
            } else {
                showToast("Erreur lors de la copie", "error");
            }
        } catch (err) {
            console.error("Erreur lors de la copie: ", err);
            showToast("Erreur lors de la copie", "error");
        }

        document.body.removeChild(textArea);
    }

    function showToast(message, type = "success") {
        // Create toast element
        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-sm font-medium shadow-lg transition-all duration-300 ${
            type === "success"
                ? "bg-green-500 text-white"
                : "bg-red-500 text-white"
        }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.add("translate-x-0", "opacity-100");
            toast.classList.remove("translate-x-full", "opacity-0");
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add("translate-x-full", "opacity-0");
            toast.classList.remove("translate-x-0", "opacity-100");
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Enhanced button interactions
    document.addEventListener("DOMContentLoaded", function () {
        // Add click handlers for copy buttons
        const copyButtons = document.querySelectorAll(
            '[onclick*="navigator.clipboard.writeText"]'
        );
        copyButtons.forEach((button) => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                const email = "{{ auth()->user()->email }}";
                copyToClipboard(email);
            });
        });

        // Start auto-refresh timer
        startAutoRefresh();

        // Add smooth scrolling for better UX
        document.documentElement.style.scrollBehavior = "smooth";
    });

    // Refresh form submission
    document
        .getElementById("refresh-form")
        ?.addEventListener("submit", function (e) {
            e.preventDefault();

            // Show loading state
            const button = this.querySelector("button");
            const originalText = button.innerHTML;
            button.innerHTML =
                '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Actualisation...';
            button.disabled = true;

            // Simulate refresh (you can replace this with actual AJAX call)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                showToast("Page actualisée");
            }, 1000);
        });

    // User menu toggle functionality
    function toggleUserMenu() {
        const dropdown = document.getElementById("user-menu-dropdown");
        const button = document.getElementById("user-menu");

        if (dropdown.classList.contains("hidden")) {
            // Show dropdown
            dropdown.classList.remove("hidden", "scale-95", "opacity-0");
            dropdown.classList.add("scale-100", "opacity-100");

            // Add click outside listener
            setTimeout(() => {
                document.addEventListener("click", closeUserMenuOnClickOutside);
            }, 100);
        } else {
            // Hide dropdown
            closeUserMenu();
        }
    }

    function closeUserMenu() {
        const dropdown = document.getElementById("user-menu-dropdown");
        dropdown.classList.add("hidden", "scale-95", "opacity-0");
        dropdown.classList.remove("scale-100", "opacity-100");
        document.removeEventListener("click", closeUserMenuOnClickOutside);
    }

    function closeUserMenuOnClickOutside(event) {
        const dropdown = document.getElementById("user-menu-dropdown");
        const button = document.getElementById("user-menu");

        if (
            !dropdown.contains(event.target) &&
            !button.contains(event.target)
        ) {
            closeUserMenu();
        }
    }

    // Close dropdown when clicking on links inside it
    document
        .getElementById("user-menu-dropdown")
        ?.addEventListener("click", function (e) {
            if (
                e.target.tagName === "A" ||
                e.target.closest('button[type="submit"]')
            ) {
                setTimeout(closeUserMenu, 150);
            }
        });
</script>
