<div class="fixed bottom-6 right-6 z-50" wire:poll.15s="loadPendingContacts" wire:loading.class="animate-pulse bg-blue-50 rounded-lg">
    <!-- Bouton flottant -->
    <button wire:click="toggleDrawer" aria-label="Ouvrir l'assistant IA"
        class="w-16 h-16 rounded-full bg-indigo-600 text-white shadow-2xl flex items-center justify-center text-3xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
        </svg>
    </button>

    <!-- Drawer latéral -->
    <div wire:loading.remove wire:target="toggleDrawer,selectTrainer,closeChat"
         style="{{ $drawer ? '' : 'display: none;' }}" class="fixed inset-0 z-50 flex justify-end">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30" wire:click="$set('drawer', false); $set('active', null)"></div>
        <!-- Panneau -->
        <div class="relative w-full max-w-xs h-full bg-white shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <span class="font-bold text-lg text-indigo-700">Assistants IA</span>
                <button wire:click="$set('drawer', false); $set('active', null)"
                    class="text-gray-400 hover:text-red-500 text-2xl" aria-label="Fermer">&times;</button>
            </div>

            @if(!$active)
                <div class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Ils attendent une réponse -->
                    @if($this->pendingContacts->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">⏳ Ils attendent une réponse</h3>
                            <div class="space-y-2">
                                @foreach ($this->pendingContacts as $contact)
                                    <button wire:click="selectContact('user_{{ $contact->id }}')"
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-orange-100 hover:bg-orange-50 focus:bg-orange-100 transition group relative">
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
                                                <span class="text-xs text-gray-500 mt-1 truncate max-w-[200px]">{{ $contact->latest_message->content }}</span>
                                            @endif
                                        </span>
                                        @if($contact->unread_count > 0)
                                            <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                                {{ $contact->unread_count }}
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Assistants IA -->
                    @if($this->trainers->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">🤖 IA</h3>
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
                    @endif

                    <!-- Utilisateurs de la formation -->
                    @if($this->formationUsers->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">🏫 Dans ma formation</h3>
                            <div class="space-y-2">
                                @foreach ($this->formationUsers as $user)
                                    <button wire:click="selectContact('user_{{ $user->id }}')"
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-green-100 hover:bg-green-50 focus:bg-green-100 transition group">
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
                    <!-- Super administrateurs -->
                    @if($this->superAdmins->count() > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">👑 Superadmin</h3>
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
                </div>
            @else
                <!-- Chat containers pour IA -->
                @foreach ($this->trainers as $trainer)
                    @if($active === 'ai_' . $trainer->id)
                        <div class="flex-1 flex flex-col overflow-hidden">
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

                <!-- Chat containers pour tous les utilisateurs -->
                @foreach ($this->allChatUsers as $chatUser)
                    @if($active === 'user_' . $chatUser->id || $active === 'admin_' . $chatUser->id)
                        <div class="flex-1 flex flex-col overflow-hidden">
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
        </div>
    </div>
</div>
