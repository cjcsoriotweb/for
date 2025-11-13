@props([
    'team',
    'managers' => collect(),
])

@php
    $collection = $managers instanceof \Illuminate\Support\Collection ? $managers : collect($managers ?? []);
@endphp

  @if(!$collection->isEmpty())

<section class="rounded-3xl border border-green-200 bg-green-50/50 px-6 py-8 shadow-sm sm:px-10">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h2 class="text-2xl font-bold text-green-900">
        {{ __('Contacts lié à votre équipe') }}
      </h2>
      <p class="mt-1 text-sm text-green-600">
        {{ $team->name }}.
      </p>
    </div>
  </div>


    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($collection as $manager)
        <x-eleve.team.manager-card :team="$team" :manager="$manager" />
      @endforeach
    </div>
</section>
  @endif

