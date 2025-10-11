<div>
    @if (!empty($invitations))
    <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">


        <div>
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ __('Invitations d’équipe en attente') }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ __('Acceptez ou refusez les invitations reçues sur votre e-mail :') }}
                    <span class="font-medium text-gray-700">{{ auth()->user()->email }}</span>
                </p>
            </div>

            @if (empty($invitations))
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-gray-600">
                {{ __("Vous n'avez aucune invitation en attente.") }}
            </div>
            @else
            <div class="overflow-hidden bg-white shadow-sm rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Équipe') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Rôle') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Reçue le') }}
                            </th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($invitations as $invite)
                        <tr wire:key="invite-{{ $invite['id'] }}">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $invite['team']['name'] ?? __('(équipe supprimée)') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ !empty($invite['role']) ? ucfirst($invite['role']) : __('Membre') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">

                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="inline-flex gap-2">
                                    <button wire:click="accept({{ $invite['id'] }})"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        {{ __('Accepter') }}
                                    </button>

                                    <button wire:click="decline({{ $invite['id'] }})"
                                        class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        {{ __('Refuser') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <x-application-logo class="block h-12 w-auto" />

        <h1 class="mt-8 text-2xl font-medium text-gray-900">
            Vous n'avez aucune invitation
        </h1>

        <p class="mt-6 text-gray-500 leading-relaxed">
            {{ __("Lorsque vous recevrez une invitation à rejoindre une équipe, elle apparaîtra ici.") }}
        </p>
    </div>

    @endif
</div>