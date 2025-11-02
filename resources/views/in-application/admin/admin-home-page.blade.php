<x-app-layout
    :team="$team"
    icon="shield_person"
    :title="__('Tableau de bord administrateur')"
    :subtitle="__('Pilotez votre plateforme de formation avec une vue claire sur vos actions clés.')"
>

    <div class="relative pb-16">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-900 opacity-80 blur-3xl"></div>
        <x-admin.admin-menu-fast :team="$team" />
    </div>


</x-app-layout>


