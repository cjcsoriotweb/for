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

    @if ($showForm)
        <section class="space-y-5 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
            <header class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                        {{ $editingTrainerId ? __('Modifier le formateur IA') : __('Ajouter un formateur IA') }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $editingTrainerId
                            ? __('Actualisez le profil, l\'image et les regles du formateur.')
                            : __('Definissez les caracteristiques et l\'image de votre nouveau formateur IA.') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($editingTrainerId)
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                            {{ __('Edition en cours') }}
                        </span>
                    @endif
                    <button
                        type="button"
                        wire:click="closeForm"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                        title="{{ __('Retour a la liste') }}"
                    >
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                    </button>
                </div>
            </header>

            <form wire:submit.prevent="submitTrainer" class="space-y-5">
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
                            placeholder="llama3"
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
                            placeholder="0.7"
                        />
                        @error('temperature')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Avatar du formateur') }}
                    </label>
                    <div class="flex flex-wrap items-center gap-4">
                        <span class="inline-flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-slate-700 dark:bg-slate-800">
                            @if ($avatarUpload)
                                <img src="{{ $avatarUpload->temporaryUrl() }}" alt="{{ __('Apercu avatar') }}" class="h-full w-full object-cover" />
                            @elseif ($avatarPath)
                                <img src="{{ asset($avatarPath) }}" alt="{{ __('Avatar actuel') }}" class="h-full w-full object-cover" />
                            @else
                                <img src="{{ asset('images/ai-trainer-placeholder.svg') }}" alt="{{ __('Avatar par defaut') }}" class="h-full w-full object-cover" />
                            @endif
                        </span>
                        <div class="space-y-2">
                            <label for="ai-trainer-avatar" class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-emerald-200 px-4 py-2 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/40 dark:text-emerald-200 dark:hover:bg-emerald-500/10">
                                <span class="material-symbols-outlined text-sm">upload</span>
                                {{ __('Importer une image') }}
                            </label>
                            <input
                                id="ai-trainer-avatar"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                wire:model="avatarUpload"
                            />
                            <div class="flex flex-wrap items-center gap-2 text-[11px] text-slate-400 dark:text-slate-500">
                                <span>{{ __('PNG, JPG ou WebP · 2 Mo max') }}</span>
                                <button
                                    type="button"
                                    wire:click="usePlaceholderAvatar"
                                    class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800"
                                >
                                    <span class="material-symbols-outlined text-[13px]">refresh</span>
                                    {{ __('Reinitialiser') }}
                                </button>
                            </div>
                            @error('avatarUpload')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="ai-trainer-description" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Description (facultatif)') }}
                    </label>
                    <textarea
                        id="ai-trainer-description"
                        wire:model.defer="description"
                        rows="3"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        placeholder="{{ __('Decrivez le style ou la specialite de ce formateur.') }}"
                    ></textarea>
                    @error('description')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="ai-trainer-prompt" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Prompt / Instructions (facultatif)') }}
                    </label>
                    <textarea
                        id="ai-trainer-prompt"
                        wire:model.defer="prompt"
                        rows="5"
                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        placeholder="{{ __('Ajoutez les instructions specifiques pour ce formateur. Ex : ton, objectifs pedagogiques, etc.') }}"
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

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                        {{ __('Les formateurs crees ici pourront etre relies a vos formations.') }}
                    </p>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="closeForm"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                        >
                            <span class="material-symbols-outlined text-sm">close</span>
                            {{ __('Annuler') }}
                        </button>
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
        </section>
    @else
        <section class="space-y-4 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                        {{ __('Formateurs existants') }}
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Managez le statut et le formateur par defaut utilise par les apprenants.') }}
                    </p>
                </div>
                <button
                    type="button"
                    wire:click="startCreate"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white shadow-sm transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                    title="{{ __('Ajouter un formateur IA') }}"
                >
                    <span class="material-symbols-outlined text-lg">add</span>
                    <span class="sr-only">{{ __('Ajouter un formateur IA') }}</span>
                </button>
            </header>

            @if ($trainers->isEmpty())
                <p class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                    {{ __('Aucun formateur IA pour le moment. Creez-en un pour commencer.') }}
                </p>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($trainers as $trainer)
                        <article class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-slate-700 shadow-sm transition hover:border-emerald-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                            <button
                                type="button"
                                wire:click="editTrainer({{ $trainer->id }})"
                                class="group flex items-center justify-between gap-3 rounded-xl border border-transparent px-2 py-1 text-left transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:hover:border-emerald-500/40 dark:focus:ring-emerald-600"
                            >
                                <div class="flex items-center gap-3">
                                    <span class="relative inline-flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-full bg-emerald-100 dark:bg-emerald-500/20">
                                        <img
                                            src="{{ asset($trainer->avatar_path ?: 'images/ai-trainer-placeholder.svg') }}"
                                            alt="{{ __('Avatar :name', ['name' => $trainer->name]) }}"
                                            class="h-full w-full object-cover"
                                        />
                                        @if ($trainer->is_default)
                                            <span class="absolute -bottom-1 -right-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-semibold uppercase text-white shadow-md">
                                                <span class="material-symbols-outlined text-xs leading-none">star</span>
                                            </span>
                                        @endif
                                    </span>
                                    <div>
                                        <span class="block text-sm font-semibold text-slate-900 transition group-hover:text-emerald-600 dark:text-slate-100 dark:group-hover:text-emerald-200">
                                            {{ $trainer->name }}
                                        </span>
                                        <span class="block text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                            {{ strtoupper($trainer->provider) }} &middot; {{ $trainer->model }}
                                        </span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-slate-300 transition group-hover:text-emerald-500 dark:text-slate-500 dark:group-hover:text-emerald-300">
                                    edit
                                </span>
                            </button>

                            @if ($trainer->description)
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ \Illuminate\Support\Str::limit($trainer->description, 120) }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-2">
                                @if (! $trainer->is_default)
                                    <button
                                        type="button"
                                        wire:click="setDefault({{ $trainer->id }})"
                                        class="inline-flex items-center gap-1 rounded-full border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/40 dark:text-emerald-200 dark:hover:bg-emerald-500/10"
                                    >
                                        <span class="material-symbols-outlined text-xs">star</span>
                                        {{ __('Definir defaut') }}
                                    </button>
                                @else
                                    <button
                                        type="button"
                                        disabled
                                        class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 opacity-80 dark:bg-emerald-500/20 dark:text-emerald-200"
                                    >
                                        <span class="material-symbols-outlined text-xs">star</span>
                                        {{ __('Defaut actif') }}
                                    </button>
                                @endif

                                <button
                                    type="button"
                                    wire:click="toggleActive({{ $trainer->id }})"
                                    class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold transition focus:outline-none focus:ring-2 focus:ring-indigo-200 dark:border-slate-600 @if ($trainer->is_active) text-slate-500 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 @else text-amber-600 hover:bg-amber-50 dark:text-amber-200 dark:hover:bg-amber-900/30 @endif"
                                >
                                    <span class="material-symbols-outlined text-xs">
                                        {{ $trainer->is_active ? 'visibility' : 'visibility_off' }}
                                    </span>
                                    {{ $trainer->is_active ? __('Actif') : __('Inactif') }}
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $trainers->links() }}
                </div>
            @endif
        </section>
    @endif
</div>
