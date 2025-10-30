@props([
    'notifications' => [],
])

@php
    $initialNotifications = collect($notifications)
        ->filter(fn ($active, $key) => $active)
        ->keys()
        ->implode(',');
@endphp

<div class="assistant-dock pointer-events-none fixed bottom-6 left-1/2 -translate-x-1/2 z-[1200] flex flex-col items-end space-y-2"
  data-dock-initial-notifications="{{ $initialNotifications }}">
  <div data-dock-tooltip
    class="pointer-events-none origin-right rounded-full border border-emerald-500/60 bg-emerald-500/90 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white shadow-lg transition duration-150 ease-out opacity-0 translate-y-2 scale-95 dark:border-emerald-400/60 dark:bg-emerald-500/80">
    <span data-dock-tooltip-text></span>
  </div>
  <span class="hidden scale-0"></span>
  <span class="hidden scale-100"></span>

  <div
    class="pointer-events-auto flex items-center gap-2 rounded-3xl border border-slate-200/70 bg-white/90 px-3 py-2 shadow-2xl backdrop-blur-md dark:border-slate-700/70 dark:bg-slate-900/80">
    <button type="button" data-dock-title="Assistant IA" data-dock-target="assistant"
      class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-md transition hover:-translate-y-1 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-900 focus-visible:ring-offset-white dark:bg-slate-100 dark:text-slate-900 dark:focus-visible:ring-slate-100 dark:focus-visible:ring-offset-slate-900"
      aria-label="Ouvrir l'assistant IA" title="Assistant IA">
      <span data-dock-indicator
        class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M7 8h10"></path>
        <path d="M7 12h6"></path>
        <path
          d="M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z">
        </path>
      </svg>
    </button>

    <button type="button" data-dock-title="Professeur virtuel" data-dock-target="tutor"
      class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-500 text-white shadow-md transition hover:-translate-y-1 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-amber-500 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900"
      aria-label="Voir le professeur virtuel" title="Professeur virtuel">
      <span data-dock-indicator
        class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
        <path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path>
        <path d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path>
        <path d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
      </svg>
    </button>

    <button type="button" data-dock-title="Signaler un bug" data-dock-target="bug"
      class="dock-action relative flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-md transition hover:-translate-y-1 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-sky-500 focus-visible:ring-offset-white dark:focus-visible:ring-offset-slate-900"
      aria-label="Signaler un bug" title="Signaler un bug">
      <span data-dock-indicator
        class="absolute -top-1 -right-1 h-3 w-3 scale-0 rounded-full bg-rose-500 shadow ring-2 ring-white transition-transform duration-150 ease-out dark:ring-slate-900"></span>
      <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="M10 12v-1"></path>
        <path d="M14 12v-1"></path>
        <path
          d="M12 6a5 5 0 0 0-5 5v1a7 7 0 0 0 4 6v1a1 1 0 0 0 2 0v-1a7 7 0 0 0 4-6v-1a5 5 0 0 0-5-5z">
        </path>
        <path d="M7 10H5"></path>
        <path d="M19 10h-2"></path>
        <path d="M7 16l-2 2"></path>
        <path d="M19 16l2 2"></path>
        <path d="M7 4l1.5 1.5"></path>
        <path d="M17 4 15.5 5.5"></path>
      </svg>
    </button>
  </div>
</div>

@once
  @push('scripts')
    <script>
      (function () {
        if (window.__assistantDockInitialised) {
          return;
        }
        window.__assistantDockInitialised = true;

        const EVENT_NAME = 'assistant-dock:notify';

        const setupDock = (dock) => {
          const escapeSelector = (value) => {
            if (typeof CSS !== 'undefined' && typeof CSS.escape === 'function') {
              return CSS.escape(value);
            }
            return value.replace(/[^a-zA-Z0-9_-]/g, '\\\\$&');
          };

          const tooltip = dock.querySelector('[data-dock-tooltip]');
          const tooltipText = dock.querySelector('[data-dock-tooltip-text]');
          const actions = dock.querySelectorAll('[data-dock-title]');
          const initial = (dock.getAttribute('data-dock-initial-notifications') || '')
            .split(',')
            .map((value) => value.trim())
            .filter(Boolean);

          const toggleNotification = (target, active) => {
            const action = dock.querySelector(`[data-dock-target=\"${escapeSelector(target)}\"]`);
            if (!action) {
              return;
            }
            const indicator = action.querySelector('[data-dock-indicator]');
            if (!indicator) {
              return;
            }
            indicator.classList.toggle('scale-100', Boolean(active));
            indicator.classList.toggle('scale-0', !active);
          };

          if (!tooltip || !tooltipText || actions.length === 0) {
            return;
          }

          const showTooltip = (title) => {
            if (!title) {
              hideTooltip();
              return;
            }

            tooltipText.textContent = title;
            tooltip.classList.remove('opacity-0', 'translate-y-2', 'scale-95');
            tooltip.classList.add('opacity-100', 'translate-y-0', 'scale-100');
          };

          const hideTooltip = () => {
            tooltip.classList.add('opacity-0', 'translate-y-2', 'scale-95');
            tooltip.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
          };

          actions.forEach((action) => {
            const title = action.getAttribute('data-dock-title') || action.getAttribute('title');
            const target = action.getAttribute('data-dock-target');

            action.addEventListener('mouseenter', () => showTooltip(title));
            action.addEventListener('focus', () => showTooltip(title));
            action.addEventListener('mouseleave', hideTooltip);
            action.addEventListener('blur', hideTooltip);

            if (!target) {
              return;
            }

            if (target === 'assistant') {
              action.addEventListener('click', () => {
                window.assistantDockNotify('assistant', false);

                if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                  window.Livewire.dispatch('support-toggle');
                } else {
                  window.dispatchEvent(new CustomEvent('support-toggle'));
                }
              });
            }
          });

          dock.addEventListener('mouseleave', hideTooltip);

          initial.forEach((key) => toggleNotification(key, true));

          window.addEventListener(EVENT_NAME, (event) => {
            const detail = event.detail || {};
            if (!detail || typeof detail !== 'object') {
              return;
            }

            const { target, active } = detail;
            if (!target) {
              return;
            }

            toggleNotification(target, active !== false);
          });
        };

        window.assistantDockNotify = (target, active = true) => {
          window.dispatchEvent(new CustomEvent(EVENT_NAME, {
            detail: { target, active },
          }));
        };

        document.addEventListener('DOMContentLoaded', () => {
          document.querySelectorAll('.assistant-dock').forEach(setupDock);
        });
      })();
    </script>
  @endpush
@endonce
