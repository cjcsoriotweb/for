<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selectionnez votre organisme') }}
        </h2>
        <x-deconnexion />
    </x-slot>



    <div class="py-12">
      
                @include('application-list')
          

        

        </div>


</x-app-layout>