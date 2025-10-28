<x-admin.global-layout
    icon="support_agent"
    :title="__('Support et tickets')"
    :subtitle="__('Traitez les demandes utilisateurs et centralisez les reponses.')"
>
    <div class="space-y-8">
        <livewire:support.ticket-inbox />
        <livewire:ai.trainer-tester />
    </div>
</x-admin.global-layout>
