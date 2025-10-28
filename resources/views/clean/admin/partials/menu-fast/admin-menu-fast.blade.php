<div class="space-y-12">
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-950 text-white shadow-2xl border border-white/10">
        <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-72 w-72 rounded-full bg-emerald-500/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.08),_transparent_55%)]"></div>
        <div class="relative p-8 lg:p-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.4em] text-white/50">{{ __('Espace administrateur') }}</p>
           
         

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">groups</span>
                            {{ $totalUsers }} {{ $usersLabel }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">workspace_premium</span>
                            {{ __(':active formations actives', ['active' => $activeCount]) }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">inventory_2</span>
                            {{ __('Catalogue :total', ['total' => $totalCount]) }}
                        </span>
                    </div>
                </div>

                <div class="flex-shrink-0 lg:w-72">
                    <div class="relative aspect-square w-full overflow-hidden rounded-3xl border border-white/15 bg-white/5 backdrop-blur">
                        <div class="absolute inset-6 rounded-2xl bg-gradient-to-br from-white/10 to-white/5"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            @if ($hasTeamLogo && $teamLogoUrl)
                                <img
                                    src="{{ $teamLogoUrl }}"
                                    alt="{{ __('Logo de l’équipe :name', ['name' => $team->name]) }}"
                                    class="h-32 w-32 rounded-2xl object-contain "
                                />
                            @else
                                <span class="material-symbols-outlined text-6xl text-white/70">auto_awesome</span>
                            @endif
                        </div>
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-2 text-xs font-medium uppercase tracking-[0.3em] text-white/70">
                            {{ $team->name }}
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </section>



    
    <section id="fonctionnement">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Basique') }}
        </h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('clean.admin.partials.menu-fast.stats.usersStats', ['team' => $team])
            @include('clean.admin.partials.menu-fast.stats.formationsStats', ['team' => $team])
        </div>
    </section>

    <section id="bascule">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Actions contextuelles') }}
        </h2>
        @include('clean.admin.partials.configuration')
    </section>

    
        <section id="configuration">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Vos indicateurs') }}
        </h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('clean.admin.partials.menu-fast.stats.creditTeam', ['team' => $team])


            @include('clean.admin.partials.menu-fast.stats.configurationTeam', ['team' => $team])
        </div>
    </section>
    
</div>

@if(!session('has_seen_tutzadoriala'))
<style>
/* ====== Thème Intro.js : look moderne + arrondi ====== */
.introjs-tailwind .introjs-tooltip {
border-radius: 1rem; /* 16px */
padding: 1rem 1rem 0.75rem;
box-shadow: 0 10px 25px rgba(0,0,0,.18);
border: 1px solid rgba(0,0,0,.06);
}
.introjs-tailwind .introjs-tooltip-header {
display: none; /* on gère nos titres dans le contenu */
}
.introjs-tailwind .introjs-tooltiptext {
font-size: 0.95rem;
line-height: 1.45rem;
color: #0f172a; /* slate-900 */
}
.introjs-tailwind .introjs-bullets ul li a {
width: 8px; height: 8px; border-radius: 9999px;
background: #e2e8f0; /* slate-200 */
}
.introjs-tailwind .introjs-bullets ul li a.active {
background: #0ea5e9; /* sky-500 */
transform: scale(1.15);
}
.introjs-tailwind .introjs-progress {
height: 6px; background: #e2e8f0; border-radius: 9999px; overflow: hidden;
}
.introjs-tailwind .introjs-progressbar {
background: linear-gradient(90deg, #0ea5e9, #22c55e); /* sky->green */
}
.introjs-tailwind .introjs-button {
border-radius: 9999px; padding: .5rem .9rem; font-weight: 600;
border: 1px solid transparent; transition: all .15s ease;
}
.introjs-tailwind .introjs-nextbutton { background:#0ea5e9; color:white; }
.introjs-tailwind .introjs-prevbutton { background:white; color:#0f172a; border-color:#e5e7eb; }
.introjs-tailwind .introjs-skipbutton, .introjs-tailwind .introjs-donebutton {
background:#0f172a; color:white;
}
.introjs-tailwind .introjs-button:hover { filter: brightness(0.95); }


/* Halo de surbrillance doux et arrondi autour des éléments ciblés */
.introjs-helperLayer { border-radius: 1rem !important; box-shadow: 0 0 0 4px rgba(14,165,233,.35), 0 0 0 9999px rgba(2,6,23,.55) !important; }
.introjs-overlay { background: rgba(2,6,23,.55) !important; }


/* Petites animations d'apparition */
.introjs-showElement, .introjs-floating {
animation: pop .16s ease-out;
}
@keyframes pop { from { transform: scale(.98); opacity: .6; } to { transform: scale(1); opacity: 1; } }
</style>


  <script>
    // Helpers
    const $ = (sel) => document.querySelector(sel);
    const show = (el) => el.classList.remove('hidden');
    const hide = (el) => el.classList.add('hidden');
    const toast = (msg = 'Action effectuée ✅') => {
      const t = $('#toast');
      t.querySelector('div').textContent = msg;
      t.classList.remove('hidden');
      setTimeout(() => t.classList.add('hidden'), 1600);
    };

    // Chat mock
    $('#chat-button').addEventListener('click', () => { show($('#chat-panel')); toast('Chat ouvert'); });
    $('#chat-close').addEventListener('click', () => { hide($('#chat-panel')); toast('Chat fermé'); });

    // Bouton pour (re)lancer le guide
    $('#help-tour').addEventListener('click', () => startTour(true));

    // Lancer automatiquement la 1ère fois (localStorage pour éviter la répétition)
    document.addEventListener('DOMContentLoaded', () => {
      if (!localStorage.getItem('tour_seen')) {
        startTour(false);
        localStorage.setItem('tour_seen', '1');
      }
    });

    function startTour(fromButton) {
      const tour = introJs();
      tour.setOptions({
        steps: [
           {
                intro: "Salut {{ Auth::user()->name }}! 👋"
            },
            {
                element: document.querySelector('#fonctionnement'),
                intro: "Les outils principal à l'administration sont ici."
            },
                        {
                element: document.querySelector('#configuration'),
                intro: "La configuration de l'application."
            },
            {
                element: document.querySelector('#bascule'),
                intro: "Vous n'êtes pas obligé d'être en mode administrateur d'application."
            },
        ],
        showProgress: true,
        showBullets: true,
        exitOnOverlayClick: false,
        nextLabel: 'Suivant →',
        prevLabel: '← Précédent',
        doneLabel: 'Terminer',
        hidePrev: false,
        hideNext: false,
        tooltipClass: 'introjs-tailwind',
        highlightClass: 'introjs-highlight-round',
        disableInteraction: false,
      });

      // Callbacks pour synchroniser l'UI pendant le tour
      tour.onchange(function(targetElement) {
        // Quand on arrive sur l'étape "ouvrir le chat", si fermé → ouvrir pour la suite
        const step = tour._currentStep;
        const id = tour._options.steps[step]?.element?.id;
        if (id === 'chat-panel' || id === 'chat-close') {
          show($('#chat-panel'));
        }
      });

      tour.oncomplete(function(){ toast('Guide terminé'); });
      tour.onexit(function(){ if(fromButton) toast('Guide fermé'); });

      tour.start();
    }
  </script>

@endif