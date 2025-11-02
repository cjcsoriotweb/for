@php
  use Illuminate\Support\Str;
  $defaultTrainerSlug = config('ai.default_trainer_slug', 'default');
@endphp

<x-app-layout>
  <div class="py-10">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <a href="{{ route('formateur.formation.show', $formation) }}"
             class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Retour a la formation') }}
          </a>
          <h1 class="mt-4 text-3xl font-bold text-gray-900">
            {{ __('Formateur IA pour : :title', ['title' => $formation->title]) }}
          </h1>
          <p class="mt-2 text-sm text-gray-600">
            {{ __('Selectionnez un formateur IA actif pour cette formation ou choisissez de ne pas en utiliser.') }}
          </p>
        </div>
        <div class="hidden sm:block">
          <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
            {{ __('IA') }}
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

      <div class="bg-white shadow-sm ring-1 ring-gray-100/70 sm:rounded-2xl">
        <div class="border-b border-gray-100 px-6 py-4">
          <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Parametrage du formateur IA') }}
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            {{ __('Un formateur IA permet aux eleves de dialoguer avec un assistant dedie a cette formation.') }}
          </p>
        </div>

        <div class="px-6 py-6">
          @if ($categories->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center">
              <p class="text-sm text-gray-600">
                {{ __('Aucune categorie nest disponible pour le moment. Contactez un administrateur pour en creer.') }}
              </p>
            </div>
          @else
            <form method="POST" action="{{ route('formateur.formation.ai.update', $formation) }}" class="space-y-6">
              @csrf
              @method('PUT')

              <fieldset class="space-y-4">
                <legend class="text-sm font-medium text-gray-700">
                  {{ __('Choisissez la categorie associee a cette formation') }}
                </legend>

                <div class="flex flex-col gap-3">
                  <label class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 hover:border-indigo-200">
                    <div class="flex items-center gap-3">
                      <input
                        type="radio"
                        name="category_id"
                        value=""
                        @checked(!$selectedCategoryId)
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                      />
                      <div>
                        <p class="text-sm font-semibold text-gray-900">
                          {{ __('Aucune categorie') }}
                        </p>
                        <p class="text-xs text-gray-500">
                          {{ __('Les eleves ne verront que les assistants generiques ou ceux ajoutes manuellement.') }}
                        </p>
                      </div>
                    </div>
                  </label>

                  @foreach ($categories as $category)
                    <label class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 hover:border-indigo-200">
                      <div class="flex items-center gap-3">
                        <input
                          type="radio"
                          name="category_id"
                          value="{{ $category->id }}"
                          @checked($selectedCategoryId === $category->id)
                          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                        />
                        <div>
                          <p class="text-sm font-semibold text-gray-900">
                            {{ $category->name }}
                          </p>
                          @if ($category->description)
                            <p class="text-xs text-gray-500">
                              {{ Str::limit($category->description, 120) }}
                            </p>
                          @endif
                          @if ($category->aiTrainer)
                            <p class="text-xs text-gray-500">
                              {{ __('Assistant IA : :name', ['name' => $category->aiTrainer->name]) }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                              {{ __('Modele : :model', ['model' => $category->aiTrainer->model ?: __('defaut')]) }}
                            </p>
                          @else
                            <p class="mt-1 text-xs text-gray-400">
                              {{ __('Aucun assistant IA associe a cette categorie') }}
                            </p>
                          @endif
                        </div>
                      </div>
                      @if ($category->aiTrainer && $category->aiTrainer->slug === $defaultTrainerSlug)
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-emerald-700">
                          {{ __('Defaut') }}
                        </span>
                      @endif
                    </label>
                  @endforeach
                </div>
              </fieldset>

              <div class="flex items-center justify-end gap-3">
                <a
                  href="{{ route('formateur.formation.show', $formation) }}"
                  class="inline-flex items-center rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                  {{ __('Annuler') }}
                </a>
                <button
                  type="submit"
                  class="inline-flex items-center rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                >
                  {{ __('Enregistrer la categorie') }}
                </button>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
