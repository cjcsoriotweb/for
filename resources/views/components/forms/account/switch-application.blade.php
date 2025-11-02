@props(['application'])

@php
    $role = auth()->user()->teamRole($application);
    $roleLabel = $role?->name ?? \Illuminate\Support\Str::headline($role?->key ?? 'membre');
@endphp

<form method="POST" action="{{ route('user.switch', $application) }}" {{ $attributes }}>
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
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
        class="group/btn w-full relative overflow-hidden rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200 transition-all duration-500 hover:shadow-xl hover:ring-2 hover:ring-blue-500/20 hover:scale-[1.02] dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:bg-slate-800 dark:hover:ring-blue-400/30"
    >
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 opacity-0 transition-all duration-500 group-hover/btn:opacity-100 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-purple-900/20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-slate-50/50 opacity-0 transition-opacity duration-500 group-hover/btn:opacity-100 dark:to-slate-800/50"></div>
        <div class="absolute inset-0 flex items-center justify-center opacity-8 group-hover/btn:opacity-15 transition-opacity duration-500">
            @if ($application->profile_photo_url)
                <img
                    src="{{ $application->profile_photo_url }}"
                    alt=""
                    class="h-40 w-40 rounded-2xl opacity-30 group-hover/btn:opacity-50 transition-opacity duration-500"
                    style="object-fit: scale-down"
                />
            @else
                <div class="h-40 w-40 rounded-2xl bg-gradient-to-br from-blue-400/10 via-purple-500/10 to-indigo-600/10 flex items-center justify-center backdrop-blur-sm">
                    <span class="material-symbols-outlined text-5xl text-slate-400 dark:text-slate-500">business</span>
                </div>
            @endif
        </div>

        <div class="relative z-10">
            <div class="mb-5 flex items-center justify-between gap-4 text-sm">
                <div class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:bg-blue-500/10 dark:text-blue-200">
                    <span class="material-symbols-outlined mr-2 text-sm">workspace_premium</span>
                    <span>{{ __($roleLabel) }}</span>
                </div>
                <div class="hidden sm:flex items-center gap-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                    <span class="material-symbols-outlined text-base">groups_3</span>
                    <span>{{ __('Votre équipe') }} adz</span>
                </div>
            </div>

            <div class="mb-6 flex h-20 w-20 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-white via-slate-50 to-slate-100 ring-2 ring-slate-200/50 dark:from-slate-700/50 dark:via-slate-600/50 dark:to-slate-500/50 dark:ring-slate-600/30 group-hover/btn:ring-blue-300/50 dark:group-hover/btn:ring-blue-400/50 transition-all duration-500 group-hover/btn:scale-105">
                @if ($application->profile_photo_url)
                    <div class="relative h-full w-full rounded-2xl overflow-hidden">
                        <img
                            src="{{ $application->profile_photo_url }}"
                            alt="{{ $application->name }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover/btn:scale-110"
                        />
                    </div>
                @else
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 via-purple-500 to-indigo-600 rounded-2xl opacity-90 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-300 via-purple-400 to-indigo-500 rounded-2xl opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative rounded-2xl bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 p-4 group-hover/btn:scale-105 transition-transform duration-500">
                            <span class="material-symbols-outlined text-3xl text-white drop-shadow-sm">business</span>
                        </div>
                        <div class="absolute inset-0 rounded-2xl ring-2 ring-blue-400/30 scale-100 group-hover/btn:scale-110 opacity-0 group-hover/btn:opacity-100 transition-all duration-500"></div>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white group-hover/btn:text-blue-600 dark:group-hover/btn:text-blue-400 transition-colors duration-300 leading-tight">
                    {{ $application->name }}
                </h3>
                <div class="flex items-center justify-center text-sm font-medium text-blue-600 dark:text-blue-400 group-hover/btn:text-blue-700 dark:group-hover/btn:text-blue-300">
                    <span class="material-symbols-outlined text-lg mr-2 transition-transform duration-300 group-hover/btn:scale-110">rocket_launch</span>
                    <span>{{ __('Accéder') }}</span>
                </div>
            </div>

            <div class="absolute top-4 right-4 opacity-0 transition-all duration-500 group-hover/btn:opacity-100">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-blue-500 to-purple-600 transform scale-x-0 transition-transform duration-500 group-hover/btn:scale-x-100 origin-left"></div>
        </div>
    </button>
</form>
