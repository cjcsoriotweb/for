<x-guest-layout>
    <x-ui::auth.form-page
        title="Créer un compte"
        subtitle="Rejoignez notre plateforme"
        footer-text="Déjà inscrit ?"
        footer-link-text="Se connecter"
        footer-link-url="{{ route('login') }}"
    >
        <x-slot name="fields">
            <livewire:auth.register-form />
        </x-slot>
    </x-ui::auth.form-page>
</x-guest-layout>
