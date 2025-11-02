<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                {{ __('Assistants disponibles') }}
            </h2>
            <button
                type="button"
                wire:click="create"
                class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700"
            >
                <span class="material-symbols-outlined text-base">add</span>
                {{ __('Nouvel assistant') }}
            </button>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-300">
                        <th class="px-4 py-3">{{ __('Nom') }}</th>
                        <th class="px-4 py-3">{{ __('Slug') }}</th>
                        <th class="px-4 py-3">{{ __('Modele') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('Actif') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('Afficher partout') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm dark:divide-slate-700">
                    @forelse ($trainers as $trainer)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3 font-medium text-slate-900 dark:text-slate-100">{{ $trainer->name }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $trainer->slug }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $trainer->model ?? config('ai.default_model') }}</td>
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleActive({{ $trainer->id }})"
                                    class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $trainer->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}"
                                >
                                    {{ $trainer->is_active ? __('Actif') : __('Inactif') }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button
                                    type="button"
                                    wire:click="toggleShowEverywhere({{ $trainer->id }})"
                                    class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $trainer->show_everywhere ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-200' : 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}"
                                >
                                    {{ $trainer->show_everywhere ? __('Oui') : __('Non') }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $trainer->id }})"
                                        class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:border-blue-300 hover:text-blue-600 dark:border-slate-600 dark:text-slate-300"
                                    >
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                        {{ __('Editer') }}
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $trainer->id }})"
                                        onclick="return confirm('{{ __('Supprimer cet assistant ?') }}')"
                                        class="inline-flex items-center gap-1 rounded-full border border-red-200 px-3 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-500/40 dark:text-red-300"
                                    >
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                        {{ __('Supprimer') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __('Aucun assistant configure pour le moment.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200/70 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($showForm)
            <form wire:submit.prevent="save" class="space-y-4 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ $trainerId ? __('Modifier l\'assistant') : __('Nouvel assistant') }}
                </h3>

                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Slug') }}</label>
                        <input type="text" wire:model.defer="form.slug" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" placeholder="default" />
                        @error('form.slug') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Nom') }}</label>
                        <input type="text" wire:model.defer="form.name" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" />
                        @error('form.name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Description') }}</label>
                        <textarea wire:model.defer="form.description" rows="2" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Modele') }}</label>
                            <input type="text" wire:model.defer="form.model" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" placeholder="llama3" />
                        </div>
                        <div class="grid gap-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Temperature') }}</label>
                            <input type="number" step="0.01" min="0" max="2" wire:model.defer="form.temperature" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" />
                            @error('form.temperature') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Garde fou') }}</label>
                        <input type="text" wire:model.defer="form.guard" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" />
                    </div>

                    <div class="grid gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Prompt : voici a quoi tu sers') }}</label>
                        <textarea wire:model.defer="form.prompt_purpose" rows="3" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="grid gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Prompt : ce que tu peux dire') }}</label>
                        <textarea wire:model.defer="form.prompt_allowed" rows="3" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="grid gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Prompt : ce que tu ne peux pas dire') }}</label>
                        <textarea wire:model.defer="form.prompt_not_allowed" rows="3" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="grid gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Regles importantes') }}</label>
                        <textarea wire:model.defer="form.prompt_rules" rows="3" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="grid gap-3">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Prompt personalise (optionnel)') }}</label>
                        <textarea wire:model.defer="form.prompt_custom" rows="4" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800"></textarea>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model.defer="form.use_tools" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800" />
                            {{ __('Autoriser les outils Evolubat') }}
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model.defer="form.is_active" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800" />
                            {{ __('Assistant actif') }}
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                            <input type="checkbox" wire:model.defer="form.show_everywhere" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800" />
                            {{ __('Afficher partout') }}
                        </label>
                    </div>

                    <div class="grid gap-2">
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Ordre d\'affichage') }}</label>
                        <input type="number" min="0" wire:model.defer="form.sort_order" class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-600 dark:bg-slate-800" />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button
                        type="button"
                        wire:click="cancel"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300 dark:border-slate-600 dark:text-slate-300"
                    >
                        {{ __('Annuler') }}
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700"
                    >
                        <span class="material-symbols-outlined text-base">save</span>
                        {{ __('Enregistrer') }}
                    </button>
                </div>
            </form>
        @else
            <div class="rounded-3xl border border-dashed border-slate-300/70 bg-slate-50 px-6 py-8 text-sm text-slate-500 dark:border-slate-600/60 dark:bg-slate-800/40 dark:text-slate-300">
                {{ __('Selectionnez un assistant pour le modifier ou cliquez sur "Nouvel assistant" pour en creer un nouveau.') }}
            </div>
        @endif
    </div>
</div>
