<div class="space-y-6">
    @if (session('ai_trainer_created'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-900/30 dark:text-emerald-100">
            {{ session('ai_trainer_created') }}
        </div>
    @endif

    @if (session('ai_trainer_updated'))
        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-500/40 dark:bg-blue-900/30 dark:text-blue-100">
            {{ session('ai_trainer_updated') }}
        </div>
    @endif

    <form wire:submit.prevent="submitTrainer" class="space-y-4 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                    {{ $editingTrainerId ? __('Modifier le formateur IA') : __('Creer un nouveau formateur IA') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $editingTrainerId
                        ? __('Mettez a jour les informations de ce formateur IA.')
                        : __('Definissez un prompt et un modele pour rendre ce formateur disponible aux formations.') }}
                </p>
            </div>
            @if ($editingTrainerId)
                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                    {{ __('Edition en cours') }}
                </span>
            @endif
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
                <label for="ai-trainer-name" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Nom du formateur') }}
                </label>
                <input
                    id="ai-trainer-name"
                    type="text"
                    wire:model.defer="name"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    placeholder="{{ __('Ex: Formateur Maconnerie IA') }}"
                />
                @error('name')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="ai-trainer-provider" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Fournisseur') }}
                </label>
                <select
                    id="ai-trainer-provider"
                    wire:model.defer="provider"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                >
                    @foreach ($providerOptions as $option)
                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                    @endforeach
                </select>
                @error('provider')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="ai-trainer-model" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Modele') }}
                </label>
                <input
                    id="ai-trainer-model"
                    type="text"
                    wire:model.defer="model"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    placeholder="gpt-4o-mini"
                />
                @error('model')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="ai-trainer-temperature" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('Temperature') }}
                </label>
                <input
                    id="ai-trainer-temperature"
                    type="number"
                    step="0.1"
                    min="0"
                    max="2"
                    wire:model.defer="temperature"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                />
                @error('temperature')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label for="ai-trainer-description" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                {{ __('Description courte') }}
            </label>
            <textarea
                id="ai-trainer-description"
                rows="2"
                wire:model.defer="description"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                placeholder="{{ __('Ex: Specialiste des techniques de maconnerie traditionnelle...') }}"
            ></textarea>
            @error('description')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="ai-trainer-prompt" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                {{ __('Prompt systeme') }}
            </label>
            <textarea
                id="ai-trainer-prompt"
                rows="5"
                wire:model.defer="prompt"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                placeholder="{{ __('Decrivez le role, le ton et les connaissances du formateur...') }}"
            ></textarea>
            @error('prompt')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center gap-6">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                <input type="checkbox" wire:model.defer="isDefault" class="rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
                {{ __('Definir comme formateur par defaut') }}
            </label>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                <input type="checkbox" wire:model.defer="isActive" class="rounded border-slate-300 text-emerald-500 focus:ring-emerald-400">
                {{ __('Activer immediatement') }}
            </label>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {{ __('Les formateurs crees ici pourront etre associes aux formations.') }}
            </p>
            <div class="flex items-center gap-2">
                @if ($editingTrainerId)
                    <button
                        type="button"
                        wire:click="cancelEdit"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                    >
                        <span class="material-symbols-outlined text-sm">close</span>
                        {{ __('Annuler') }}
                    </button>
                @endif
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                >
                    <span class="material-symbols-outlined text-base">{{ $editingTrainerId ? 'save' : 'add' }}</span>
                    {{ $editingTrainerId ? __('Mettre a jour le formateur') : __('Ajouter le formateur') }}
                </button>
            </div>
        </div>
    </form>

    <section class="space-y-4 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
        <header class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                    {{ __('Formateurs existants') }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Managez le statut et le formateur par defaut utilise par les apprenants.') }}
                </p>
            </div>
        </header>

        @if (empty($trainers))
            <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                {{ __('Aucun formateur IA pour le moment. Creez-en un pour commencer.') }}
            </p>
        @else
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($trainers as $trainer)
                    <article class="flex flex-col justify-between gap-3 rounded-2xl border border-slate-200 px-4 py-4 text-sm text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-base font-semibold">
                                    {{ $trainer['name'] }}
                                </h4>
                                @if ($trainer['is_default'])
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">
                                        <span class="material-symbols-outlined text-xs">star</span>
                                        {{ __('Defaut') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ strtoupper($trainer['provider']) }} &middot; {{ $trainer['model'] }}
                            </p>
                            @if ($trainer['description'])
                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $trainer['description'] }}
                                </p>
                            @endif
                            <p class="text-xs text-slate-400 dark:text-slate-500">
                                {{ __('Mis a jour : :date', ['date' => $trainer['updated_at_human']]) }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                wire:click="editTrainer({{ $trainer['id'] }})"
                                class="inline-flex items-center gap-1 rounded-full border border-indigo-200 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-indigo-500/40 dark:text-indigo-200"
                            >
                                <span class="material-symbols-outlined text-xs">edit</span>
                                {{ __('Editer') }}
                            </button>

                            @if (! $trainer['is_default'])
                                <button
                                    type="button"
                                    wire:click="setDefault({{ $trainer['id'] }})"
                                    class="inline-flex items-center gap-1 rounded-full border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/40 dark:text-emerald-200"
                                >
                                    <span class="material-symbols-outlined text-xs">star</span>
                                    {{ __('Definir defaut') }}
                                </button>
                            @endif

                            <button
                                type="button"
                                wire:click="toggleActive({{ $trainer['id'] }})"
                                class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-600 @if ($trainer['is_active']) text-slate-500 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 @else text-amber-600 hover:bg-amber-50 dark:text-amber-200 dark:hover:bg-amber-900/30 @endif"
                            >
                                <span class="material-symbols-outlined text-xs">
                                    {{ $trainer['is_active'] ? 'visibility' : 'visibility_off' }}
                                </span>
                                {{ $trainer['is_active'] ? __('Actif') : __('Inactif') }}
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
