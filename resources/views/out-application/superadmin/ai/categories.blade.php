<x-admin.global-layout
    icon="category"
    :title="__('Categories de formation IA')"
    :subtitle="__('Classez vos formations et reliez-les aux assistants IA les plus pertinents.')"
>
    <div class="space-y-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a
                href="{{ route('superadmin.ai.index') }}"
                class="inline-flex items-center text-sm font-medium text-indigo-600 transition hover:text-indigo-800 dark:text-indigo-300 dark:hover:text-indigo-200"
            >
                <span class="material-symbols-outlined mr-1 text-base">arrow_back</span>
                {{ __('Retour a la console IA') }}
            </a>
            <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                {{ __('Superadmin uniquement') }}
            </span>
        </div>

        <div class="space-y-12">
            <livewire:superadmin.formation-category-manager />
        </div>
    </div>
</x-admin.global-layout>
