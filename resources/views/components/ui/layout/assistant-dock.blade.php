<x-dock>
  <x-dock-slot>

    <!-- Contenu personnalisé pour chaque fonctionnalité via named slots -->
    <x-slot:chatia>
      <iframe src="{{ route('user.dock.assistant-chat') }}" class="w-full h-full border-0"></iframe>
    </x-slot:chatia>

    <x-slot:tutorial>
      <iframe src="{{ route('user.dock.tutorials') }}" class="w-full h-full border-0"></iframe>
    </x-slot:tutorial>

    <x-slot:tutor>
      <iframe src="{{ route('user.dock.professeur') }}" class="w-full h-full border-0"></iframe>
    </x-slot:tutor>

    <x-slot:support>
      <iframe src="{{ route('user.dock.support') }}" class="w-full h-full border-0"></iframe>
    </x-slot:support>

    <x-slot:search>
      <iframe src="{{ route('user.dock.recherche') }}" class="w-full h-full border-0"></iframe>
    </x-slot:search>

  </x-dock-slot>
</x-dock>
