<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <x-forms.auth.reset-password :request="$request" />
    </x-authentication-card>
</x-guest-layout>
