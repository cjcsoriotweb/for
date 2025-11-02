@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('superadmin.ai.index') }}"
                       class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ __('Retour a la console IA') }}
                    </a>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">
                        {{ __('Categories de formations') }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 max-w-2xl">
                        {{ __('Creez des categories pour organiser les formations. Chaque categorie peut etre associee a un assistant IA specifique. Les formateurs peuvent ensuite selectionner la categorie appropriee pour leurs formations.') }}
                    </p>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                        {{ __('Superadmin') }}
                    </span>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm ring-1 ring-gray-100/70 sm:rounded-2xl">
                        <div class="border-b border-gray-100 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ __('Nouvelle categorie') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ __('Definissez une categorie et, si besoin, associez-lui un assistant IA actif.') }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('superadmin.formation-categories.store') }}" class="px-6 py-6 space-y-5">
                            @csrf
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    {{ __('Nom de la categorie') }}
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    required
                                    maxlength="120"
                                    value="{{ old('name') }}"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    {{ __('Description (optionnel)') }}
                                </label>
                                <textarea
                                    name="description"
                                    id="description"
                                    rows="4"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >{{ old('description') }}</textarea>
                            </div>

                            <div>
                                <label for="ai_trainer_id" class="block text-sm font-medium text-gray-700">
                                    {{ __('Assistant IA associe (optionnel)') }}
                                </label>
                                <select
                                    name="ai_trainer_id"
                                    id="ai_trainer_id"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">{{ __('Aucun assistant specifique') }}</option>
                                    @foreach ($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" @selected(old('ai_trainer_id') == $trainer->id)>
                                            {{ $trainer->name }} @if($trainer->model) - {{ $trainer->model }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ __('Seuls les assistants actifs peuvent etre choisis.') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                >
                                    {{ __('Creer la categorie') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm ring-1 ring-gray-100/70 sm:rounded-2xl">
                        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ __('Categories existantes') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ __('Modifiez le nom, la description ou lassistant IA associe. Supprimez une categorie uniquement si elle nest plus utilisee.') }}
                                </p>
                            </div>
                            <span class="text-sm text-gray-400">{{ trans_choice(':count categorie|:count categories', $categories->count(), ['count' => $categories->count()]) }}</span>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @forelse ($categories as $category)
                                <div class="px-6 py-5">
                                    <form method="POST" action="{{ route('superadmin.formation-categories.update', $category) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">
                                                    {{ __('Nom') }}
                                                </label>
                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ old('name_'.$category->id, $category->name) }}"
                                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    maxlength="120"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">
                                                    {{ __('Assistant IA associe') }}
                                                </label>
                                                <select
                                                    name="ai_trainer_id"
                                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                    <option value="">{{ __('Aucun assistant specifique') }}</option>
                                                    @foreach ($trainers as $trainer)
                                                        <option value="{{ $trainer->id }}" @selected(old('ai_trainer_id_'.$category->id, $category->ai_trainer_id) == $trainer->id)>
                                                            {{ $trainer->name }} @if($trainer->model) - {{ $trainer->model }} @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">
                                                {{ __('Description') }}
                                            </label>
                                            <textarea
                                                name="description"
                                                rows="3"
                                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >{{ old('description_'.$category->id, $category->description) }}</textarea>
                                        </div>

                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="text-xs text-gray-500">
                                                {{ trans_choice(':count formation associee|:count formations associees', $category->formations_count, ['count' => $category->formations_count]) }}
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                >
                                                    {{ __('Mettre a jour') }}
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="if (confirm('{{ __("Supprimer cette categorie ? Les formations rattachees perdront leur categorie.") }}')) document.getElementById('delete-category-{{ $category->id }}').submit();"
                                                    class="inline-flex items-center rounded-full bg-red-50 px-4 py-1.5 text-sm font-semibold text-red-600 transition hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200"
                                                >
                                                    {{ __('Supprimer') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="delete-category-{{ $category->id }}" method="POST" action="{{ route('superadmin.formation-categories.destroy', $category) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center text-sm text-gray-500">
                                    {{ __('Aucune categorie nest encore definie.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
