@php
    use Illuminate\Support\Str;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white shadow-sm ring-1 ring-gray-100/70 sm:rounded-2xl">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        {{ $categoryId ? __('Modifier la categorie') : __('Nouvelle categorie') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Creez vos categories pour structurer les catalogues de formations.') }}
                    </p>
                </div>
                <button wire:click="{{ $showForm ? 'cancel' : 'create' }}"
                    class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">
                    {{ $showForm ? __('Annuler') : __('+ Ajouter') }}
                </button>
            </div>

            @if (session('status'))
                <div class="px-6 py-3 text-sm text-green-700 bg-green-50 border-b border-green-100">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="px-6 py-3 text-sm text-red-600 bg-red-50 border-b border-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($showForm)
                <form wire:submit.prevent="save" class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Nom de la categorie') }}
                        </label>
                        <input type="text" wire:model.defer="form.name" maxlength="120"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Description (optionnel)') }}
                        </label>
                        <textarea wire:model.defer="form.description" rows="4"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="cancel"
                            class="inline-flex items-center rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            {{ __('Enregistrer') }}
                        </button>
                    </div>
                </form>
            @else
                <div class="px-6 py-8 text-center text-sm text-gray-500">
                    {{ __('Selectionnez une categorie a gauche pour la modifier ou cliquez sur "+ Ajouter".') }}
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white shadow-sm ring-1 ring-gray-100/70 sm:rounded-2xl">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        {{ __('Categories existantes') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Chaque categorie peut etre assignee aux formations par les formateurs.') }}
                    </p>
                </div>
                <span class="text-xs font-medium text-gray-400 uppercase">
                    {{ trans_choice(':count categorie|:count categories', $categories->count(), ['count' => $categories->count()]) }}
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <div class="px-6 py-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $category->name }}</h3>
                                    <div class="mt-1 text-xs text-gray-500 space-y-1">
                                        @if ($category->description)
                                            <p>{{ Str::limit($category->description, 160) }}</p>
                                        @endif
                                        @unless ($category->description)
                                            <p class="text-gray-400">{{ __('Aucune description fournie.') }}</p>
                                        @endunless
                                    </div>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-gray-600">
                                    {{ trans_choice(':count formation|:count formations', $category->formations_count, ['count' => $category->formations_count]) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="edit({{ $category->id }})"
                                class="inline-flex items-center rounded-full bg-indigo-50 px-4 py-1.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">
                                {{ __('Modifier') }}
                            </button>
                            <button wire:click="delete({{ $category->id }})"
                                onclick="if(!confirm('{{ __('Supprimer cette categorie ? Les formations rattachees perdront leur categorie.') }}')) { event.stopImmediatePropagation(); return false; }"
                                class="inline-flex items-center rounded-full bg-red-50 px-4 py-1.5 text-sm font-semibold text-red-600 hover:bg-red-100">
                                {{ __('Supprimer') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">
                        {{ __('Aucune categorie nest encore definie.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
