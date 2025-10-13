<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    <x-block-div>
        <x-slot name="block">
            Configuration de votre application
        </x-slot>
        

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

                <x-admin-block 
                    titre="Changer nom application"
                    description="Gérez les paramètres de votre application, y compris les préférences utilisateur, les options de sécurité et les intégrations tierces."
                />

                <x-admin-block 
                    titre="Changer logo"
                    description="Gérez les paramètres de votre application, y compris les préférences utilisateur, les options de sécurité et les intégrations tierces."
                />
        </section>


    </x-block-div>


</x-application-layout>