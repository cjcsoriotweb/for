@props([
    // Which buttons to show
    'buttons' => [
        'page-search' => true,
        'assistant' => true,
        'tutor' => true,
        'bug' => true,
    ],
    // Per-button state: enabled, locked, wizz, notify (int)
    // Example usage:
    // x-ui.layout.assistant-dock -state="['assistant' => ['enabled' => true, 'locked' => false, 'wizz' => true, 'notify' => 3]]" />
    'state' => [],
    // AI configuration for assistant
    'chatmodel' => null,
    'chatprovider' => null,
])

@php
    $defaults = [
        'enabled' => true,
        'locked' => false,
        'wizz' => false,
        'notify' => 0,
    ];

    $resolve = function (string $key) use ($state, $defaults) {
        $cfg = array_merge($defaults, (array) ($state[$key] ?? []));
        return (object) [
            'enabled' => (bool) ($cfg['enabled'] ?? true),
            'locked' => (bool) ($cfg['locked'] ?? false),
            'wizz' => (bool) ($cfg['wizz'] ?? false),
            'notify' => (int) max(0, (int) ($cfg['notify'] ?? 0)),
        ];
    };

    $assistantCfg = $resolve('assistant');
    $tutorCfg = $resolve('tutor');
    $bugCfg = $resolve('bug');
    $searchCfg = $resolve('page-search');

    // initial notifications string "key:count,key:count" for JS
    $initialNotifications = collect([
        'assistant' => $assistantCfg->notify,
        'tutor' => $tutorCfg->notify,
        'bug' => $bugCfg->notify,
        'page-search' => $searchCfg->notify,
    ])->filter(fn ($v) => (int) $v > 0)
      ->map(fn ($v, $k) => $k . ':' . (int) $v)
      ->implode(',');
@endphp

<x-dock>
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

      @if ($buttons['page-search'] ?? false)
      <x-dock-slot name="page-search"
                   mode="search"
                   title="Recherche page"
                   action="page-search"
                   :enabled="$searchCfg->enabled"
                   :locked="$searchCfg->locked"
                   :wizz="$searchCfg->wizz"
                   :notify="$searchCfg->notify" />
      @endif

      @if ($buttons['assistant'] ?? false)
      <x-dock-slot name="assistant"
                   mode="chatia"
                   title="Assistant IA"
                   action="chatia"
                   :chatmodel="$chatmodel"
                   :chatprovider="$chatprovider"
                   :enabled="$assistantCfg->enabled"
                   :locked="$assistantCfg->locked"
                   :wizz="$assistantCfg->wizz"
                   :notify="$assistantCfg->notify" />
      @endif

      @if ($buttons['tutor'] ?? false)
      <x-dock-slot name="tutor"
                   mode="tutor"
                   title="Professeur virtuel"
                   action="tutor"
                   :enabled="$tutorCfg->enabled"
                   :locked="$tutorCfg->locked"
                   :wizz="$tutorCfg->wizz"
                   :notify="$tutorCfg->notify" />
      @endif

      @if ($buttons['bug'] ?? false)
      <x-dock-slot name="bug"
                   mode="support"
                   title="Signaler un bug"
                   action="support"
                   :enabled="$bugCfg->enabled"
                   :locked="$bugCfg->locked"
                   :wizz="$bugCfg->wizz"
                   :notify="$bugCfg->notify" />
      @endif
    </div>
  </div>
</x-dock>

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
            .filter(Boolean)
            .map((pair) => {
              const [key, count] = pair.split(':');
              return { key, count: parseInt(count || '0', 10) || 0 };
            });

          const toggleNotification = (target, active) => {
            const action = dock.querySelector(`[data-dock-target=\"${escapeSelector(target)}\"]`);
            if (!action) {
              return;
            }
            const indicator = action.querySelector('[data-dock-indicator]');
            const badge = action.querySelector('[data-dock-badge]');

            if (typeof active === 'number') {
              const count = Math.max(0, active|0);
              if (badge) {
                if (count > 0) {
                  badge.textContent = String(count);
                  badge.classList.remove('hidden');
                } else {
                  badge.classList.add('hidden');
                }
              }
              if (indicator) {
                indicator.classList.add('scale-0');
                indicator.classList.remove('scale-100');
              }
              return;
            }

            if (indicator) {
              indicator.classList.toggle('scale-100', Boolean(active));
              indicator.classList.toggle('scale-0', !active);
            }
            if (badge) {
              badge.classList.add('hidden');
            }
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
            const enabled = action.getAttribute('data-dock-enabled') !== '0';
            const locked = action.getAttribute('data-dock-locked') === '1';
            const wizz = action.getAttribute('data-dock-wizz') === '1';
            const notify = parseInt(action.getAttribute('data-dock-notify') || '0', 10) || 0;

            action.addEventListener('mouseenter', () => showTooltip(title));
            action.addEventListener('focus', () => showTooltip(title));
            action.addEventListener('mouseleave', hideTooltip);
            action.addEventListener('blur', hideTooltip);

            if (!target) {
              return;
            }

            if (!enabled) {
              action.classList.add('opacity-60', 'cursor-not-allowed');
            }
            if (wizz) {
              action.classList.add('animate-pulse');
            }
            if (locked) {
              action.setAttribute('aria-disabled', 'true');
            }

            if (notify > 0) {
              toggleNotification(target, notify);
            }

            if (target === 'assistant') {
              action.addEventListener('click', () => {
                if (!enabled) return;
                window.assistantDockNotify('assistant', false);

                // Open the AI chat (assistant)
                if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                  window.Livewire.dispatch('assistant-toggle');
                } else {
                  window.dispatchEvent(new CustomEvent('assistant-toggle'));
                }
              });
            }

            if (target === 'bug') {
              action.addEventListener('click', () => {
                if (!enabled) return;
                window.assistantDockNotify('bug', false);

                // Open the support ticket widget
                if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                  window.Livewire.dispatch('support-toggle');
                } else {
                  window.dispatchEvent(new CustomEvent('support-toggle'));
                }
              });
            }

            if (target === 'tutor') {
              action.addEventListener('click', () => {
                if (!enabled) return;
                window.assistantDockNotify('tutor', false);

                // Open the tutor chat (same chat IA, but can be specialized by component)
                if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                  window.Livewire.dispatch('tutor-toggle');
                } else {
                  window.dispatchEvent(new CustomEvent('tutor-toggle'));
                }
              });
            }

            if (target === 'page-search') {
              action.addEventListener('click', () => {
                window.assistantDockNotify('page-search', false);
                if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
                  window.Livewire.dispatch('page-search-toggle');
                } else {
                  window.dispatchEvent(new CustomEvent('page-search-toggle'));
                }
              });
            }
          });

          dock.addEventListener('mouseleave', hideTooltip);

          initial.forEach(({ key, count }) => toggleNotification(key, count));

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
