<div class="fixed bottom-6 right-6 z-50" wire:loading.class="animate-pulse bg-blue-50 rounded-lg">
    <div class="flex flex-col items-end gap-3">
        <!-- Bouton Assistants IA -->
        <button wire:click="toggleDrawer('ia')" aria-label="Ouvrir le menu des assistants IA"
            @class([
                'w-14 h-14 rounded-full text-white shadow-2xl flex items-center justify-center transition relative focus:outline-none focus:ring-2 focus:ring-offset-2',
                'bg-indigo-700 ring-4 ring-indigo-200 focus:ring-indigo-500' => $drawer && $drawerTab === 'ia',
                'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500' => !($drawer && $drawerTab === 'ia'),
            ])>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
            </svg>
        </button>

        <!-- Bouton Contacts -->
        <button  wire:poll.5s="loadPendingContacts" wire:click="toggleDrawer('contacts')" aria-label="Ouvrir le menu des contacts"
            @class([
                'w-14 h-14 rounded-full text-white shadow-2xl flex items-center justify-center transition relative focus:outline-none focus:ring-2 focus:ring-offset-2',
                'bg-green-700 ring-4 ring-green-200 focus:ring-green-500' => $drawer && $drawerTab === 'contacts',
                'bg-green-600 hover:bg-green-700 focus:ring-green-500' => !($drawer && $drawerTab === 'contacts'),
            ])>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 12a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21a8.25 8.25 0 1116.5 0H3.75z" />
            </svg>
            @if($this->pendingTotalUnread > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 min-w-[1.25rem] px-1 flex items-center justify-center font-semibold">
                    {{ $this->pendingTotalUnread > 99 ? '99+' : $this->pendingTotalUnread }}
                </span>
            @endif
        </button>

        <!-- Bouton Notifications -->
        <button wire:poll.10s="refreshAiNotifications" wire:click="toggleDrawer('notifications')" aria-label="Ouvrir le menu des notifications"
            @class([
                'w-14 h-14 rounded-full text-white shadow-2xl flex items-center justify-center transition relative focus:outline-none focus:ring-2 focus:ring-offset-2',
                'bg-amber-700 ring-4 ring-amber-200 focus:ring-amber-500' => $drawer && $drawerTab === 'notifications',
                'bg-amber-600 hover:bg-amber-700 focus:ring-amber-500' => !($drawer && $drawerTab === 'notifications'),
            ])>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9a6 6 0 00-12 0v.75a8.967 8.967 0 01-2.312 6.022c1.74.64 3.56 1.085 5.455 1.31M14.857 17.082a3.001 3.001 0 01-5.714 0" />
            </svg>
            @if($this->notifications > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 min-w-[1.25rem] px-1 flex items-center justify-center font-semibold">
                    {{ $this->notifications > 99 ? '99+' : $this->notifications }}
                </span>
            @endif
        </button>
    </div>

    <!-- Drawer latéral -->
    <div wire:loading.remove wire:target="toggleDrawer,selectContact,closeChat"
         style="{{ $drawer ? '' : 'display: none;' }}" class="fixed inset-0 z-50 flex justify-end">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30" wire:click="closeDrawer"></div>
        <!-- Panneau -->
        <div class="relative w-full max-w-xs h-full bg-white shadow-2xl flex flex-col">
            @php
                $drawerTitle = match($drawerTab) {
                    'contacts' => 'Contacts',
                    'notifications' => 'Notifications',
                    'ia' => 'Assistants IA',
                    default => 'Menu',
                };
                $titleClass = match($drawerTab) {
                    'contacts' => 'text-green-700',
                    'notifications' => 'text-amber-700',
                    default => 'text-indigo-700',
                };
            @endphp
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <span class="font-bold text-lg {{ $titleClass }}">{{ $drawerTitle }}</span>
                <button wire:click="closeDrawer"
                    class="text-gray-400 hover:text-red-500 text-2xl" aria-label="Fermer">&times;</button>
            </div>

            @if(!$active || $drawerTab === 'notifications')
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    @if($drawerTab === 'ia')
                        @if($this->trainers->count() > 0)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Assistants IA</h3>
                                <div class="space-y-2">
                                    @foreach ($this->trainers as $trainer)
                                        <button wire:click="selectContact('ai_{{ $trainer->id }}')"
                                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-indigo-100 hover:bg-indigo-50 focus:bg-indigo-100 transition group">
                                            <span class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                                {{ strtoupper(mb_substr($trainer->name,0,2)) }}
                                            </span>
                                            <span class="flex flex-col items-start flex-1">
                                                <span class="text-gray-900 font-semibold text-base group-hover:text-indigo-700">{{ $trainer->name }}</span>
                                                @if(!empty($trainer->description))
                                                    <span class="text-xs text-gray-500 mt-1">{{ $trainer->description }}</span>
                                                @endif
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center text-sm text-gray-500 py-6">
                                Aucun assistant IA disponible pour le moment.
                            </div>
                        @endif
                    @elseif($drawerTab === 'contacts')
                        <div class="space-y-5">
                            <div class="bg-white/90 border border-slate-200 rounded-2xl shadow-sm p-5">
                                <h3 class="text-base font-semibold text-slate-800">Ajouter un contact</h3>
                                <p class="text-xs text-slate-500 mt-1">Saisissez l'adresse e-mail de votre interlocuteur pour lancer une nouvelle conversation.</p>
                                <form wire:submit.prevent="addContactByEmail" class="mt-4 flex flex-col gap-3">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                        <div class="flex-1">
                                            <input type="email" wire:model.defer="addContactEmail"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                placeholder="prenom.nom@exemple.com">
                                        </div>
                                        <button type="submit"
                                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60"
                                            wire:loading.attr="disabled" wire:target="addContactByEmail">
                                            Ajouter
                                        </button>
                                    </div>
                                    @if($addContactError)
                                        <p class="text-sm text-red-600">{{ $addContactError }}</p>
                                    @endif
                                    @if($addContactSuccess)
                                        <p class="text-sm text-green-600">{{ $addContactSuccess }}</p>
                                    @endif
                                </form>
                            </div>

                            @if($this->manualContacts->count() > 0)
                                <div class="bg-white/90 border border-slate-200 rounded-2xl shadow-sm p-5">
                                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-3">Contacts rapides</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->manualContacts as $contact)
                                            <button wire:click="selectContact('{{ $contact->chat_contact_id }}')"
                                                class="w-full flex items-center gap-3 rounded-xl border border-slate-200/70 bg-slate-50/60 px-4 py-3 text-left transition hover:bg-slate-100 focus:bg-slate-100">
                                                @if($contact->profile_photo_path)
                                                    <img src="{{ $contact->profile_photo_url }}" alt="{{ $contact->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <span class="w-10 h-10 rounded-full {{ $contact->superadmin ? 'bg-red-500' : 'bg-indigo-500' }} text-white flex items-center justify-center font-bold text-lg">
                                                        {{ strtoupper(mb_substr($contact->name,0,2)) }}
                                                    </span>
                                                @endif
                                                <span class="flex flex-col items-start flex-1">
                                                    <span class="text-slate-800 font-semibold text-base">{{ $contact->name }}</span>
                                                    <span class="text-xs text-slate-500 mt-1">Conversation à venir</span>
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($this->pendingContacts->count() > 0)
                                <div class="bg-white/90 border border-orange-200 rounded-2xl shadow-sm p-5">
                                    <h3 class="text-sm font-semibold text-orange-600 uppercase tracking-wide mb-3">Ils attendent une reponse</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->pendingContacts as $contact)
                                            <button wire:click="selectContact('{{ $contact->chat_contact_id }}')"
                                                class="w-full flex items-center gap-3 rounded-xl border border-orange-200 bg-orange-50/70 px-4 py-3 text-left transition hover:bg-orange-100 focus:bg-orange-100 relative">
                                                @if($contact->profile_photo_path)
                                                    <img src="{{ $contact->profile_photo_url }}" alt="{{ $contact->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <span class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold text-lg">
                                                        {{ strtoupper(mb_substr($contact->name,0,2)) }}
                                                    </span>
                                                @endif
                                                <span class="flex flex-col items-start flex-1">
                                                    <span class="text-gray-900 font-semibold text-base group-hover:text-orange-700">{{ $contact->name }}</span>
                                                    @if($contact->latest_message)
                                                        <span class="text-xs text-slate-500 mt-1 truncate max-w-[220px]">{{ $contact->latest_message->content }}</span>
                                                    @endif
                                                </span>
                                                @if($contact->unread_count > 0)
                                                    <span class="bg-red-500 text-white text-xs rounded-full h-5 min-w-[1.25rem] px-1 flex items-center justify-center font-bold">
                                                        {{ $contact->unread_count > 99 ? '99+' : $contact->unread_count }}
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($this->conversationContacts->count() > 0)
                                <div class="bg-white/90 border border-slate-200 rounded-2xl shadow-sm p-5">
                                    <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-3">Conversations recentes</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->conversationContacts as $contact)
                                            <button wire:click="selectContact('{{ $contact->chat_contact_id }}')"
                                                class="w-full flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3 text-left transition hover:bg-slate-50 focus:bg-slate-100">
                                                @if($contact->profile_photo_path)
                                                    <img src="{{ $contact->profile_photo_url }}" alt="{{ $contact->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <span class="w-10 h-10 rounded-full {{ $contact->superadmin ? 'bg-red-500' : 'bg-teal-500' }} text-white flex items-center justify-center font-bold text-lg">
                                                        {{ strtoupper(mb_substr($contact->name,0,2)) }}
                                                    </span>
                                                @endif
                                                <span class="flex flex-col items-start flex-1">
                                                    <span class="text-gray-900 font-semibold text-base group-hover:text-slate-800">{{ $contact->name }}</span>
                                                    @if($contact->latest_message)
                                                        <span class="text-xs text-slate-500 mt-1 truncate max-w-[220px]">
                                                            {{ \Illuminate\Support\Str::limit($contact->latest_message->content ?? '', 90) }}
                                                        </span>
                                                    @endif
                                                </span>
                                                @if($contact->latest_message_at)
                                                    <span class="text-xs text-slate-400">
                                                        {{ $contact->latest_message_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($this->formationUsers->count() > 0)
                                <div class="bg-white/90 border border-emerald-200 rounded-2xl shadow-sm p-5">
                                    <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wide mb-3">Dans ma formation</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->formationUsers as $user)
                                            <button wire:click="selectContact('user_{{ $user->id }}')"
                                                class="w-full flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-left transition hover:bg-emerald-100 focus:bg-emerald-100">
                                                @if($user->profile_photo_path)
                                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <span class="w-10 h-10 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-lg">
                                                        {{ strtoupper(mb_substr($user->name,0,2)) }}
                                                    </span>
                                                @endif
                                                <span class="flex flex-col items-start flex-1">
                                                    <span class="text-gray-900 font-semibold text-base group-hover:text-green-700">{{ $user->name }}</span>
                                                    <span class="text-xs text-gray-500 mt-1">Participant à la formation</span>
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($this->superAdmins->count() > 0)
                                <div>
                                    <h3 class="text-sm font-semibold text-rose-600 uppercase tracking-wide mb-3">Superadmins</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->superAdmins as $admin)
                                            <button wire:click="selectContact('admin_{{ $admin->id }}')"
                                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-red-100 hover:bg-red-50 focus:bg-red-100 transition group">
                                                @if($admin->profile_photo_path)
                                                    <img src="{{ $admin->profile_photo_url }}" alt="{{ $admin->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <span class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-lg">
                                                        {{ strtoupper(mb_substr($admin->name,0,2)) }}
                                                    </span>
                                                @endif
                                                <span class="flex flex-col items-start flex-1">
                                                    <span class="text-gray-900 font-semibold text-base group-hover:text-red-700">{{ $admin->name }}</span>
                                                    <span class="text-xs text-gray-500 mt-1">Administrateur</span>
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(
                                $this->pendingContacts->count() === 0 &&
                                $this->conversationContacts->count() === 0 &&
                                $this->formationUsers->count() === 0 &&
                                $this->superAdmins->count() === 0
                            )
                                <div class="text-center text-sm text-gray-500 py-6">
                                    Aucun contact disponible pour le moment.
                                </div>
                            @endif
                        </div>
                    @elseif($drawerTab === 'notifications')
                        <div class="space-y-3">
                            @if($this->pendingAiNotifications->count() > 0)
                                <div class="bg-white/90 border border-indigo-200 rounded-2xl shadow-sm p-5">
                                    <h3 class="text-sm font-semibold text-indigo-600 uppercase tracking-wide mb-3">Réponses des assistants IA</h3>
                                    <div class="space-y-2">
                                        @foreach ($this->pendingAiNotifications as $aiNotification)
                                            <div class="rounded-xl border border-indigo-200 bg-indigo-50/70 px-4 py-3">
                                                <div class="flex items-start gap-3">
                                                    @if($aiNotification->contact_id)
                                                        <div
                                                            class="flex-1 flex items-start gap-3 cursor-pointer rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/60"
                                                            role="button"
                                                            tabindex="0"
                                                            wire:click="selectContact('{{ $aiNotification->contact_id }}')"
                                                        >
                                                            <span class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                                                {{ strtoupper(mb_substr($aiNotification->assistant_name,0,2)) }}
                                                            </span>
                                                            <span class="flex flex-col">
                                                                <span class="text-gray-900 font-semibold text-base">{{ $aiNotification->assistant_name }}</span>
                                                                @if($aiNotification->preview)
                                                                    <span class="text-xs text-gray-600 mt-1">{{ $aiNotification->preview }}</span>
                                                                @endif
                                                                @if($aiNotification->source_preview)
                                                                    <span class="text-[11px] text-gray-400 mt-1 italic">Vous : {{ $aiNotification->source_preview }}</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="flex-1 flex items-start gap-3">
                                                            <span class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                                                {{ strtoupper(mb_substr($aiNotification->assistant_name,0,2)) }}
                                                            </span>
                                                            <span class="flex flex-col">
                                                                <span class="text-gray-900 font-semibold text-base">{{ $aiNotification->assistant_name }}</span>
                                                                @if($aiNotification->preview)
                                                                    <span class="text-xs text-gray-600 mt-1">{{ $aiNotification->preview }}</span>
                                                                @endif
                                                                @if($aiNotification->source_preview)
                                                                    <span class="text-[11px] text-gray-400 mt-1 italic">Vous : {{ $aiNotification->source_preview }}</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="flex flex-col items-end gap-2">
                                                        <span class="text-xs text-gray-400">{{ $aiNotification->created_at->diffForHumans() }}</span>
                                                        <button type="button"
                                                            wire:click="removeNotification({{ $aiNotification->id }})"
                                                            class="text-xs text-indigo-700 hover:text-indigo-900 underline">
                                                            Retirer notification
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($this->formattedNotifications->count() > 0)
                                @foreach ($this->formattedNotifications as $notification)
                                    <div class="rounded-xl border {{ $notification->is_read ? 'border-gray-200 bg-gray-50' : 'border-amber-200 bg-amber-50' }} p-4">
                                        <div class="flex items-start justify-between gap-2">
                                            <span class="font-semibold text-gray-900">{{ $notification->title }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                <button type="button"
                                                    wire:click="markNotificationAsRead('{{ $notification->id }}')"
                                                    class="text-xs text-amber-700 hover:text-amber-900 underline">
                                                    Marquer comme lu
                                                </button>
                                            </div>
                                        </div>
                                        @if($notification->message)
                                            <div class="text-sm text-gray-600 mt-2 whitespace-pre-line">{{ $notification->message }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            @elseif($this->pendingAiNotifications->count() === 0)
                                <div class="text-center text-sm text-gray-500 py-6">
                                    Aucune notification récente.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center text-sm text-gray-500 py-6">
                            Sélectionnez un menu pour afficher son contenu.
                        </div>
                    @endif
                </div>
            @else
                @if($drawerTab === 'ia')
                    @foreach ($this->trainers as $trainer)
                        @if($active === 'ai_' . $trainer->id)
                            <div class="flex-1 flex flex-col overflow-hidden" wire:ignore>
                                <div class="flex items-center gap-2 px-5 py-3 border-b bg-indigo-50">
                                    <button wire:click="closeChat" class="text-indigo-600 hover:text-indigo-900 text-xl mr-2" aria-label="Retour">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    <span class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                        {{ strtoupper(mb_substr($trainer->name,0,2)) }}
                                    </span>
                                    <span class="font-semibold text-indigo-900 text-lg">{{ $trainer->name }}</span>
                                </div>

                                @livewire('chat-box', [
                                    'contactId' => 'ai_' . $trainer->id,
                                    'contactType' => 'ai',
                                    'title' => $trainer->name
                                ], key('chatbox-' . $trainer->id))
                            </div>
                        @endif
                    @endforeach
                @elseif($drawerTab === 'contacts')
                    @foreach ($this->allChatUsers as $chatUser)
                        @if($active === 'user_' . $chatUser->id || $active === 'admin_' . $chatUser->id)
                            <div class="flex-1 flex flex-col overflow-hidden"  wire:ignore>
                                <div class="flex items-center gap-2 px-5 py-3 border-b {{ $chatUser->superadmin ? 'bg-red-50' : 'bg-green-50' }}">
                                    <button wire:click="closeChat" class="{{ $chatUser->superadmin ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} text-xl mr-2" aria-label="Retour">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                        </svg>
                                    </button>
                                    @if($chatUser->profile_photo_path)
                                        <img src="{{ $chatUser->profile_photo_url }}" alt="{{ $chatUser->name }}"
                                            class="w-9 h-9 rounded-full object-cover">
                                    @else
                                        <span class="w-9 h-9 rounded-full {{ $chatUser->superadmin ? 'bg-red-600' : 'bg-green-600' }} text-white flex items-center justify-center font-bold text-lg">
                                            {{ strtoupper(mb_substr($chatUser->name,0,2)) }}
                                        </span>
                                    @endif
                                    <span class="font-semibold {{ $chatUser->superadmin ? 'text-red-900' : 'text-green-900' }} text-lg">{{ $chatUser->name }}</span>
                                </div>

                                @livewire('chat-box', [
                                    'contactId' => ($chatUser->superadmin ? 'admin_' : 'user_') . $chatUser->id,
                                    'contactType' => $chatUser->superadmin ? 'admin' : 'user',
                                    'title' => $chatUser->name
                                ], key('chatbox-user-' . $chatUser->id))
                            </div>
                        @endif
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</div>

