<div class="w-full">
    <!-- Header Section -->
    @include('livewire.formation.formation-lesson-list.header')

    <!-- Lessons List -->
    <div class="bg-white">
        @forelse($lessons as $lesson)
        @include('livewire.formation.formation-lesson-list.lesson-item',
        ['lesson' => $lesson]) @empty
        @include('livewire.formation.formation-lesson-list.empty-state')
        @endforelse
    </div>
</div>
