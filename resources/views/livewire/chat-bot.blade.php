<div>
    <!-- Bouton flottant modernise pour l'assistant IA -->
<div class="pointer-events-none fixed inset-0 z-[9999] flex items-end justify-end p-5 sm:p-6">
  <button
    type="button"
    aria-label="Discuter avec l'assistant IA"
    class="assistant-launcher pointer-events-auto group relative flex items-center gap-4 overflow-hidden rounded-full border border-white/15
           bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-5 py-2.5 text-white shadow-2xl shadow-indigo-600/40
           transition-all duration-200 hover:-translate-y-0.5 hover:border-white/25 hover:shadow-pink-500/40 focus:outline-none
           focus-visible:ring-4 focus-visible:ring-pink-200/60"
    data-assistant-trigger>

    <!-- Halo anime -->
    <span aria-hidden="true" class="assistant-launcher__ring"></span>
    <span aria-hidden="true" class="assistant-launcher__beam"></span>
    <span aria-hidden="true" class="assistant-launcher__glow"></span>
    <span aria-hidden="true" class="assistant-launcher__spark"></span>

    <!-- Texte -->
    <span class="flex flex-col text-left leading-tight">
      <span class="text-[10px] font-semibold uppercase tracking-[0.45em] text-white/70">Assistant</span>
      <span class="text-base font-semibold tracking-tight">Parler maintenant</span>
    </span>

    <!-- Pulse decorative -->
    <span aria-hidden="true" class="assistant-launcher__pulse"></span>

  </button>
</div>

<!-- Modal iframe -->
<div
  id="assistant-modal"
  class="assistant-modal hidden fixed inset-0 z-[10000] items-center justify-center bg-slate-950/70 backdrop-blur-sm p-4 sm:p-8"
  role="dialog"
  aria-modal="true"
  aria-hidden="true">
  <div class="relative w-full max-w-5xl h-[80vh] rounded-3xl bg-white shadow-2xl shadow-slate-900/50 overflow-hidden">
    <button
      type="button"
      class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
      aria-label="Fermer le chat IA"
      data-assistant-close>
      ✕
    </button>

    <iframe
      src="{{config('services.goodview_genie_url')}}?models= "
      title="Assistant IA"
      class="h-full w-full border-0"
      loading="lazy"></iframe>
  </div>
</div>

<style>
  .assistant-launcher {
    opacity: 1;
    animation: idle-fade 1s ease forwards;
    animation-delay: 5s;
    animation-fill-mode: forwards;
  }

  .assistant-launcher__ring {
    position: absolute;
    inset: -45%;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.35), transparent 65%);
    opacity: 0.4;
    animation: pulse-ring 4s ease-in-out infinite;
    pointer-events: none;
  }

  .assistant-launcher__beam {
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, rgba(255, 255, 255, 0) 45%, rgba(255, 255, 255, 0.4) 55%, rgba(255, 255, 255, 0) 65%);
    transform: translateX(-120%);
    animation: shimmer 6s linear infinite;
    pointer-events: none;
  }

  .assistant-launcher__glow {
    position: absolute;
    inset: -30%;
    border-radius: 999px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, transparent 60%);
    filter: blur(12px);
    opacity: 0.35;
    pointer-events: none;
  }

  .assistant-launcher__spark {
    position: absolute;
    width: 140%;
    height: 140%;
    left: -10%;
    top: -40%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    opacity: 0.25;
    transform: rotate(6deg);
    animation: spark-drift 5s ease-in-out infinite;
    pointer-events: none;
  }

  .assistant-launcher__pulse {
    display: inline-flex;
    width: 12px;
    height: 12px;
    border-radius: 999px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    position: relative;
  }

  .assistant-launcher__pulse::after {
    content: "";
    position: absolute;
    inset: 2px;
    border-radius: inherit;
    background: #aef5c5;
    box-shadow: 0 0 12px rgba(174, 245, 197, 0.85);
    animation: pulse-dot 2.4s ease-in-out infinite;
  }

  .assistant-launcher:hover,
  .assistant-launcher:focus-visible {
    opacity: 1;
    animation: none;
  }

  @keyframes pulse-ring {
    0%, 100% { opacity: 0.15; transform: scale(0.85); }
    50% { opacity: 0.4; transform: scale(1); }
  }

  @keyframes shimmer {
    0% { transform: translateX(-120%); }
    100% { transform: translateX(120%); }
  }

  @keyframes spark-drift {
    0%, 100% { transform: translateX(-10%) rotate(6deg); opacity: 0.2; }
    50% { transform: translateX(10%) rotate(6deg); opacity: 0.35; }
  }

  @keyframes pulse-dot {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(0.65); opacity: 0.5; }
    100% { transform: scale(1); opacity: 1; }
  }

  @keyframes idle-fade {
    0% { opacity: 1; }
    100% { opacity: 0.12; }
  }
</style>

<script>
  (() => {
    const initAssistantModal = () => {
      const trigger = document.querySelector('[data-assistant-trigger]');
      const modal = document.getElementById('assistant-modal');
      const closeBtn = modal?.querySelector('[data-assistant-close]');

      if (!trigger || !modal || modal.dataset.initialized === 'true') return;

      const openModal = () => {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        modal.focus?.();
      };

      const closeModal = () => {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
      };

      trigger.addEventListener('click', openModal);
      closeBtn?.addEventListener('click', closeModal);

      modal.addEventListener('click', (event) => {
        if (event.target === modal) {
          closeModal();
        }
      });

      window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') {
          closeModal();
        }
      });

      modal.dataset.initialized = 'true';
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initAssistantModal, { once: true });
    } else {
      initAssistantModal();
    }
  })();
</script>

<div class="min-h-screen w-full bg-slate-950 text-slate-100"
    @if (! is_null($conversationId)) wire:poll.1s="fetchMessage" @endif>
    <div class="mx-auto flex h-screen max-w-4xl flex-col px-4 py-6 sm:px-6 lg:px-0">
        @if ($selectingModel)
            <div
                class="flex h-full flex-col overflow-hidden rounded-3xl  shadow-2xl backdrop-blur">
                <div
                    class="flex flex-wrap items-center justify-between gap-4 border-b border-white/5 bg-slate-900/80 px-6 py-5">
                    <div>
                        <p class="text-2xl font-semibold text-white">Choisissez un modele</p>
                        <p class="mt-1 text-sm text-white/70">
                            Selectionnez le profil d'assistant qui repondra a votre nouvelle conversation.
                        </p>
                    </div>
                    <button type="button" wire:click="backToConversations"
                        class="inline-flex items-center gap-2 rounded-2xl border border-white/10 px-4 py-2 text-sm font-medium text-white transition hover:border-white/40 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="grid gap-6 sm:grid-cols-2">
                        @forelse ($models as $model)
                            <button type="button" wire:click="chooseModel({{ $model['id'] }})"
                                class="group flex h-full flex-col rounded-3xl border border-white/10 bg-white/5 p-6 text-left transition hover:border-emerald-400/70 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
                                @if (! empty($model['image']))
                                    <img src="{{ asset($model['image']) }}" alt="{{ $model['name'] }}"
                                        class="h-16 w-16 rounded-2xl object-cover" />
                                @else
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-lg font-bold uppercase text-white/80">
                                        {{ strtoupper(mb_substr($model['name'], 0, 2)) }}
                                    </div>
                                @endif
                                <p class="mt-4 text-lg font-semibold text-white">{{ $model['name'] }}</p>
                                @if (! empty($model['description']))
                                    <p class="mt-2 text-sm text-white/70">{{ $model['description'] }}</p>
                                @endif
                    
                            </button>
                        @empty
                            <div
                                class="col-span-full rounded-3xl border border-dashed border-white/15 bg-slate-900/60 p-8 text-center text-white/70">
                                <p class="text-base font-semibold">Aucun modele disponible.</p>
                                <p class="mt-2 text-sm">Ajoutez des modeles pour pouvoir lancer une conversation.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @elseif (is_null($conversationId))
            <div
                class="flex h-full flex-col overflow-hidden rounded-3xl  p-8 text-white shadow-2xl  backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/5 pb-6">
                    <div>
                        <p class="text-3xl font-semibold">Bienvenue sur EvoBot</p>
                        <p class="mt-2 text-base text-white/70">
                            Choisissez une conversation existante ou lancez-en une nouvelle pour discuter avec notre assistant.
                        </p>
                    </div>
                    <button type="button" wire:click="startConversation"
                        class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle conversation
                    </button>
                </div>

                <div class="mt-6 flex-1 overflow-y-auto">
                    <div class="grid gap-4">
                        @forelse ($conversations as $conversation)
                            <button type="button" wire:click="selectConversation({{ $conversation['id'] }})"
                                wire:key="conversation-{{ $conversation['id'] }}"
                                class="group flex w-full flex-col rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-left transition hover:border-emerald-400/80 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
                                <div class="flex items-center gap-4">
                                    @if (! empty($conversation['image']))
                                        <img src="{{ asset($conversation['image']) }}" alt="{{ $conversation['title'] }}"
                                            class="h-12 w-12 rounded-2xl object-cover" />
                                    @else
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-base font-semibold text-emerald-300">
                                            EB
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-white">{{ $conversation['title'] }}</p>
                                        <p class="text-xs text-white/60">{{ $conversation['updated_at'] }}</p>
                                    </div>
                                    <span class="text-xs uppercase tracking-wide text-white/50">
                                        {{ $conversation['count'] }} message{{ $conversation['count'] > 1 ? 's' : '' }}
                                    </span>
                                </div>
                                <p class="mt-3 text-sm text-white/70">{{ $conversation['preview'] }}</p>
                            </button>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-white/15 bg-slate-900/60 px-6 py-10 text-center text-white/70">
                                <p class="text-base font-medium">Aucune conversation enregistree.</p>
                                <p class="mt-2 text-sm">Demarrez votre premiere discussion avec EvoBot.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div
                class="flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl shadow-slate-900/40 backdrop-blur">
                <header
                    class="flex flex-wrap items-center gap-4 border-b border-white/5 bg-gradient-to-r from-slate-900/80 via-slate-900/30 to-transparent px-6 py-5">
                    <div class="flex min-w-0 flex-1 items-center gap-4">
                        @if (! empty($activeModel['image'] ?? null))
                            <img src="{{ asset($activeModel['image']) }}" alt="{{ $activeModel['name'] ?? 'EvoBot' }}"
                                class="h-12 w-12 rounded-full object-cover" />
                        @else
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-xl font-semibold text-emerald-300">
                                EB
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate text-lg font-semibold text-white">
                                {{ $activeModel['name'] ?? 'EvoBot Assistant' }}
                            </p>
                            <p class="mt-1 text-sm text-white/70">
                                {{ $activeModel['description'] ?? "Disponible 7j/7 pour repondre a vos questions instantanement." }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" wire:click="backToConversations"
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 px-4 py-2 text-sm font-medium text-white transition hover:border-emerald-400 hover:text-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-400/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button type="button" wire:click="startConversation"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-slate-900 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button type="button" wire:click="clearConversation"
                            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 px-4 py-2 text-sm font-medium text-white transition hover:border-rose-400 hover:text-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-400/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7h12" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m10 11 .01 6M14 11l-.01 6" />
                            </svg>
                        </button>
                    </div>
                </header>

                <div class="flex-1 overflow-hidden">
                    <div id="chatMessages" class="flex h-full flex-col gap-6 overflow-y-auto px-6 py-6">
                        @forelse ($messages as $index => $message)
                            <div class="space-y-3" wire:key="message-{{ $message->id ?? $index }}">
                                <div class="flex justify-end">
                                    <div
                                        class="max-w-[80%] rounded-3xl bg-emerald-500 px-5 py-4 text-sm leading-relaxed text-slate-900 shadow-lg shadow-slate-900/40">
                                        <div
                                            class="flex items-center gap-2 text-[11px] uppercase tracking-wide text-emerald-900/70">
                                            <span>Vous</span>
                                            <span class="text-white/60">{{ $message['time'] }}</span>
                                        </div>
                                        <p class="mt-2 text-base text-emerald-950">
                                            {{ $message['text'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-start">
                                    <div wire:stream="reply-{{ $message->id }}"
                                        class="max-w-[80%] rounded-3xl bg-white/10 px-5 py-4 text-sm leading-relaxed text-white shadow-lg shadow-slate-900/40">
                                        <div
                                            class="flex items-center gap-2 text-[11px] uppercase tracking-wide text-emerald-300">
                                            <span>EvoBot</span>
                                            <span class="text-white/60">{{ $message['time'] }}</span>
                                        </div>
                                        <div class="mt-2 text-base font-semibold leading-relaxed text-white">
                                            @if ($message->reply)
                                                {!! $message->formatted_reply !!}
                                            @else

                                            <!-- Overlay réflexion IA (à rendre seulement pendant le stream) -->
<div
  class="fixed inset-0 z-40 flex items-center justify-center
         pointer-events-none
         bg-black/10 backdrop-blur-[1px]"
>
  <!-- Petit pill propre au centre -->
  <div
    class="flex items-center gap-3
           rounded-full border border-white/10
           bg-slate-900/80 px-4 py-2
           shadow-lg shadow-black/40"
  >
    <!-- 3 petits points animés -->
    <div class="flex items-center gap-1.5">
      <span class="h-2 w-2 rounded-full bg-slate-300 animate-bounce"></span>
      <span class="h-2 w-2 rounded-full bg-slate-400 animate-bounce [animation-delay:120ms]"></span>
      <span class="h-2 w-2 rounded-full bg-slate-500 animate-bounce [animation-delay:240ms]"></span>
    </div>

    <span class="text-[11px] font-medium tracking-wide text-slate-100/80 uppercase">
      L’IA réfléchit…
    </span>
  </div>
</div>

                                                <div class="flex items-center gap-2 text-sm text-white/70"
                                                    x-intersect.once="$wire.look({{ $message->id }})">
                                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                                            stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                                                    </svg>
                                                    EvoBot est en train d'ecrire...
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-sm text-white/60">
                                Soyez le premier a ecrire un message.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-white/10 bg-slate-900/80 px-6 py-5">
                    <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4">
                        <div class="w-full">
                            <label for="chat-message" class="sr-only">Votre message</label>
                            <input id="chat-message" wire:model.defer="body" rows="2"
                                placeholder="Decrivez votre besoin, posez une question, demandez un devis..."
                                class="w-full resize-none rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder:text-white/50 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"></textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-rose-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:cursor-not-allowed disabled:opacity-60"
                                wire:loading.attr="disabled" wire:target="sendMessage">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Envoyer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif
    </div>

    @once
        <script>
            document.addEventListener('livewire:init', () => {
                const scrollToBottom = () => {
                    const container = document.getElementById('chatMessages');
                    if (!container) return;
                    requestAnimationFrame(() => {
                        container.scrollTop = container.scrollHeight;
                    });
                };

                scrollToBottom();

                Livewire.on('chat-scrolled', () => scrollToBottom());
            });
        </script>
    @endonce
</div>
</div>
