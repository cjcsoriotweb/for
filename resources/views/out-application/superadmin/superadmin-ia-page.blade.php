<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des assistants IA')"
    :subtitle="__('Creez, mettez a jour et pilotez les differents profils IA disponibles dans l\'application.')"
>
    <div class="space-y-12">
        <livewire:superadmin.ai-trainer-manager />
        <livewire:superadmin.formation-category-manager />
    </div>
</x-admin.global-layout>
