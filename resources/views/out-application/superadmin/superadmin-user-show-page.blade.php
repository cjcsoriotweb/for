<x-admin.global-layout
    icon="person"
    :title="__('Profil utilisateur')"
    :subtitle="$user->name"
>
    <div class="space-y-8">
        <!-- Informations générales -->
        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="border-b border-slate-200/60 bg-slate-50/80 px-6 py-4 dark:border-slate-800/80 dark:bg-slate-800/80">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    {{ __('Informations générales') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                <span class="material-symbols-outlined text-xl">person</span>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Nom complet') }}</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Email') }}</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                <span class="material-symbols-outlined text-xl">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Inscrit le') }}</p>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $user->created_at->translatedFormat('d F Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                <span class="material-symbols-outlined text-xl">group</span>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Équipe active') }}</p>
                                <p class="font-semibold text-slate-900 dark:text-white">
                                    {{ $user->currentTeam?->name ?? __('Aucune équipe active') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques des formations -->
        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="border-b border-slate-200/60 bg-slate-50/80 px-6 py-4 dark:border-slate-800/80 dark:bg-slate-800/80">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    {{ __('Statistiques des formations') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                <span class="material-symbols-outlined">school</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $formationStats['total'] }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ __('Total formations') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                <span class="material-symbols-outlined">check_circle</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $formationStats['completed'] }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ __('Terminées') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <span class="material-symbols-outlined">schedule</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $formationStats['in_progress'] }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ __('En cours') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Équipes de l'utilisateur -->
        @php
            $teams = $user->ownedTeams->merge($user->teams)->unique('id');
        @endphp
        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="border-b border-slate-200/60 bg-slate-50/80 px-6 py-4 dark:border-slate-800/80 dark:bg-slate-800/80">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                    {{ __('Équipes') }} ({{ $teams->count() }})
                </h3>
            </div>
            <div class="p-6">
                @if($teams->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($teams as $team)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-800/30">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined">group</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $team->name }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                            {{ __('Propriétaire') }}: {{ $team->owner->name }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        @php
                                            $joinedAt = $team->pivot->created_at ?? $team->created_at ?? null;
                                        @endphp
                                        {{ __('Rejoint le') }} {{ $joinedAt?->translatedFormat('d M Y') ?? __('Date inconnue') }}
                                    </p>
                                    <div class="mt-2 flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('organisateur.index', ['team' => $team->id]) }}"
                                           class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-800 transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-200 dark:hover:bg-slate-800">
                                            {{ __('Organiser') }}
                                        </a>
                                        <a href="{{ route('application.admin.index', ['team' => $team->id]) }}"
                                           class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-800 transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-200 dark:hover:bg-slate-800">
                                            {{ __('Administrateur') }}
                                        </a>
                                    </div>
                                    @if($user->current_team_id === $team->id)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('Équipe active') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-4xl text-slate-400">group_off</span>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">{{ __('Cet utilisateur ne fait partie d\'aucune équipe.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Formations de l'utilisateur -->
        @if($formations->isNotEmpty())
            <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                <div class="border-b border-slate-200/60 bg-slate-50/80 px-6 py-4 dark:border-slate-800/80 dark:bg-slate-800/80">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Formations suivies') }}
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($formations as $formation)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-800/30">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $formation->title }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-300">
                                            {{ __('Équipe') }}: {{ $formation->teams->where('id', $formation->pivot->team_id)->first()?->name ?? __('Équipe inconnue') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                @php
                                    $enrollmentDate = $formation->pivot->enrolled_at ?? $formation->pivot->created_at ?? null;
                                    $enrollmentDate = $enrollmentDate ? \Illuminate\Support\Carbon::make($enrollmentDate) : null;
                                    $teamForFormation = $formation->teams->where('id', $formation->pivot->team_id)->first();
                                @endphp
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Inscrit le') }} {{ $enrollmentDate?->translatedFormat('d M Y') ?? __('Date inconnue') }}
                                </p>
                                    @if($teamForFormation)
                                        <a href="{{ route('organisateur.formations.students.report.overview', [
                                            'team' => $teamForFormation->id,
                                            'formation' => $formation->id,
                                            'student' => $user->id,
                                        ]) }}"
                                           class="mt-2 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-800 transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-200 dark:hover:bg-slate-800">
                                            {{ __('Suivis') }}
                                        </a>
                                    @endif
                                    @if($formation->pivot->completed_at)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            {{ __('Terminée') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            {{ __('En cours') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Bouton retour -->
        <div class="flex justify-start">
            <a
                href="{{ route('superadmin.users.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
            >
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </div>
</x-admin.global-layout>
