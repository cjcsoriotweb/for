@props(['application'])

@php
    $role = auth()->user()->teamRole($application);
    $roleLabel = $role?->name ?? \Illuminate\Support\Str::headline($role?->key ?? 'membre');
@endphp

<form method="POST" action="{{ route('user.switch', $application) }}" {{ $attributes->merge(['class' => 'w-full']) }}>
    @csrf

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/50 dark:text-red-200">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button
        type="submit"
        name="team_id"
        value="{{ $application->id }}"
        aria-label="{{ __('Basculer vers l\'application :name', ['name' => $application->name]) }}"
        class="group/btn relative w-full overflow-hidden rounded-3xl bg-white p-8 shadow-lg ring-1 ring-slate-200/60 transition-all duration-300 hover:shadow-2xl hover:ring-blue-500/30 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-slate-800/60 dark:ring-slate-700/60 dark:hover:bg-slate-800 dark:hover:ring-blue-400/40 dark:focus:ring-blue-400"
    >
        <!-- Background gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 via-indigo-50/60 to-purple-50/80 opacity-0 transition-opacity duration-300 group-hover/btn:opacity-100 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-purple-900/20"></div>

        <!-- Large background image/logo -->
        <div class="absolute inset-0 flex items-center justify-center opacity-20 group-hover/btn:opacity-30 transition-opacity duration-300">
            @if ($application->profile_photo_url)
                <img
                    src="{{ $application->profile_photo_url }}"
                    alt="{{ $application->name }}"
                    class="h-32 w-32 rounded-2xl object-scale-down transition-all duration-300 group-hover/btn:scale-105"
                />
            @else
                <div class="flex h-32 w-32 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400/20 via-purple-500/20 to-indigo-600/20 backdrop-blur-sm transition-all duration-300 group-hover/btn:scale-105">
                    <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-slate-500">business</span>
                </div>
            @endif
        </div>

        <!-- Content -->
        <div class="relative z-10">
            <!-- Header with role and team info -->
            <div class="mb-6 flex items-center justify-between">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-500/15 dark:text-blue-200">
                    <span class="material-symbols-outlined text-sm">workspace_premium</span>
                    <span>{{ __($roleLabel) }}</span>
                </div>
  
            </div>

            <!-- Profile image/avatar -->
            <div class="mb-6 flex justify-center">
                <div class="relative h-20 w-20 overflow-hidden rounded-2xl bg-gradient-to-br from-white via-slate-50 to-slate-100 ring-2 ring-slate-200/50 shadow-sm transition-all duration-300 group-hover/btn:ring-blue-300/60 group-hover/btn:shadow-md dark:from-slate-700/60 dark:via-slate-600/60 dark:to-slate-500/60 dark:ring-slate-600/40 dark:group-hover/btn:ring-blue-400/60">
                    @if ($application->profile_photo_url)
                        <img
                            src="{{ $application->profile_photo_url }}"
                            alt="{{ $application->name }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover/btn:scale-110"
                        />
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 transition-all duration-300 group-hover/btn:from-blue-400 group-hover/btn:via-purple-500 group-hover/btn:to-indigo-600">
                            <span class="material-symbols-outlined text-2xl text-white drop-shadow-sm">business</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application name and action -->
            <div class="text-center">
                <h3 class="mb-3 text-xl font-bold text-slate-900 transition-colors duration-300 group-hover/btn:text-blue-600 dark:text-white dark:group-hover/btn:text-blue-400">
                    {{ $application->name }}
                </h3>
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 transition-all duration-300 group-hover/btn:bg-blue-100 group-hover/btn:text-blue-800 dark:bg-blue-500/15 dark:text-blue-300 dark:group-hover/btn:bg-blue-500/25 dark:group-hover/btn:text-blue-200">
                    <span class="material-symbols-outlined text-lg transition-transform duration-300 group-hover/btn:scale-110">rocket_launch</span>
                    <span>{{ __('Accéder') }}</span>
                </div>
            </div>

            <!-- Hover indicator arrow -->
            <div class="absolute top-4 right-4 opacity-0 transition-all duration-300 group-hover/btn:opacity-100 group-hover/btn:translate-x-0 -translate-x-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </div>

        </div>
    </button>
</form>
