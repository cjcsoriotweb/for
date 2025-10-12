<div wire:poll.3s.visible="loadInvites" class="space-y-6">
    @php
        $hasInvites = $invitations && $invitations->isNotEmpty();
        // Si tu veux masquer l'empty state quand l'utilisateur a déjà une team :
        $hasTeam = auth()->check() ? auth()->user()->teams()->exists() : false;
    @endphp

    {{-- LISTE DES INVITATIONS --}}
    @if ($hasInvites)
        <section class="bg-gray-200/50 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8 rounded-xl">
            <div>
                <header class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ __('Invitations d’équipe en attente') }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ __('Acceptez ou refusez les invitations reçues sur votre e-mail :') }}
                        <span class="font-medium text-gray-700">{{ auth()->user()->email }}</span>
                    </p>
                </header>

                <div class="overflow-hidden bg-white shadow-sm rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('Équipe') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('Rôle') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('Reçue le') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($invitations as $invite)
                            <tr wire:key="invite-{{ $invite->id }}">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $invite->team?->name ?? __('(équipe supprimée)') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $invite->role ? ucfirst($invite->role) : __('Membre') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $invite->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="inline-flex gap-2">
                                        <button
                                            wire:click.prevent="accept({{ $invite->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="accept({{ $invite->id }})"
                                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <span wire:loading.remove wire:target="accept({{ $invite->id }})">
                                                {{ __('Accepter') }}
                                            </span>
                                            <span wire:loading wire:target="accept({{ $invite->id }})">
                                                {{ __('Traitement…') }}
                                            </span>
                                        </button>

                                        <button
                                            wire:click.prevent="decline({{ $invite->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="decline({{ $invite->id }})"
                                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <span wire:loading.remove wire:target="decline({{ $invite->id }})">
                                                {{ __('Refuser') }}
                                            </span>
                                            <span wire:loading wire:target="decline({{ $invite->id }})">
                                                {{ __('Traitement…') }}
                                            </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Indicateur de polling (optionnel) --}}
                <div class="mt-3 text-xs text-gray-500" wire:loading.delay.shortest>
                    {{ __('...') }}
                </div>
            </div>
        </section>
    @endif


    @if (!$hasInvites && !$hasTeam)
        <section class="p-6 lg:p-8 bg-white border border-gray-200 rounded-xl shadow-sm">

            <h1 class="mt-6 text-xl font-semibold text-gray-900">
                {{ __("Vous n'avez aucune invitation") }}
            </h1>

            <p class="mt-3 text-gray-600 leading-relaxed">
                {{ __("Lorsque vous recevrez une invitation à rejoindre une équipe, elle apparaîtra ici.") }}
            </p>

 
        </section>
    @endif
</div>
