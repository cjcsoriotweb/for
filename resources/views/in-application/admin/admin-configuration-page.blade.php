<x-admin.layout
    :team="$team"
    :subtitle="__('Gérez les paramètres globaux de l\'application et les configurations spécifiques à l\'organisme.')"
>
    @include('in-application.admin.partials.configuration.index', ['team' => $team])
    <br />
    @include('in-application.admin.partials.home-button', ['team' => $team])
    <div class="mt-10 flex justify-end">
        <a
            href="{{ route('application.admin.configuration.credits', $team) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-emerald-600"
        >
            <span class="material-symbols-outlined text-base">credit_score</span>
            {{ __('Gerer le credit equipe') }}
        </a>
    </div>
</x-admin.layout>
