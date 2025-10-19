<div class="space-y-6">
    <!-- Formation Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        @livewire('formation.formation-edit-info', ['formation' => $formation])
    </div>

    <!-- Chapters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @livewire('formation.formation-chapter-list', ['formation' =>
        $formation])
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-blue-600"
                        >groups</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Étudiants inscrits
                    </p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $formation->learners->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-green-600"
                        >trending_up</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Taux de complétion
                    </p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $formation->learners->where('pivot.completed_at', '!=', null)->count() > 0 ? round(($formation->learners->where('pivot.completed_at', '!=', null)->count() / $formation->learners->count()) * 100) : 0








































































                        }}%
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center"
                >
                    <span class="material-symbols-outlined text-purple-600"
                        >star</span
                    >
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">
                        Note moyenne
                    </p>
                    <p class="text-2xl font-bold text-gray-900">4.8</p>
                </div>
            </div>
        </div>
    </div>
</div>
