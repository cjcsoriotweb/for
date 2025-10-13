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
                <x-button-block 
                    titre="Configurez l'application"
                    description="...."
                    url="{{ route('application.admin.configuration.index', ['team'=>$team]) }}"
                    />

 

                
        </section>


    </x-block-div>


</x-application-layout>