<div class="space-y-10">
  <header class="bg-white border border-gray-200 rounded-xl shadow-sm">
    <div class="p-8 space-y-5">
      <div class="space-y-2">
        <h1 class="text-2xl font-semibold text-gray-900">
          Gestion des formations
        </h1>
        <p class="text-sm text-gray-600">
          Supervisez l'ensemble de vos contenus et ajustez leur visibilité en quelques clics.
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-4">
        @include('in-application.admin.formations.parts.buttons.create-formation')

        <div class="inline-flex items-center gap-3 px-4 py-2 bg-gray-50 text-sm text-gray-700 rounded-lg">
          <span class="font-medium text-gray-900">
            Formations actives
          </span>
          <span
            class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
            {{ $formations->where('is_visible', true)->count() }} / {{ $formations->count() }}
          </span>
        </div>
      </div>
    </div>
  </header>

  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-semibold text-gray-900">
          Liste des formations
        </h2>
        <p class="text-sm text-gray-600">
          Retrouvez vos parcours et modifiez leur statut de publication.
        </p>
      </div>
    </div>

    @forelse($formations as $formation)
      @include('in-application.admin.formations.parts.placeholder.detail-one-of-foreach-formations')
    @empty
      @include('in-application.admin.formations.parts.placeholder.foreach-no-formation')
    @endforelse
  </section>
</div>
