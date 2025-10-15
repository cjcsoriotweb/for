<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>
<div class="flex items-center justify-end gap-3 sm:gap-4">
<div class="relative hidden lg:block">
<span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted-light dark:text-muted-dark">search</span>
<input class="form-input h-10 w-40 rounded-full border-none bg-subtle-light pl-10 text-sm placeholder:text-muted-light focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background-light dark:bg-subtle-dark dark:placeholder:text-muted-dark dark:focus:ring-offset-background-dark lg:w-56" placeholder="Search"/>
</div>
<button class="flex h-10 min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-full bg-primary px-4 text-sm font-bold text-white transition-opacity hover:opacity-90">
<span class="truncate">Sign up</span>
</button>
<button class="hidden h-10 w-10 items-center justify-center rounded-full bg-subtle-light dark:bg-subtle-dark lg:hidden">
<span class="material-symbols-outlined text-muted-light dark:text-muted-dark">search</span>
</button>
<div class="h-10 w-10 rounded-full bg-cover bg-center bg-no-repeat" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA7KO1THdPqHtFTPCngru-kVSzvnc8ZqSIdQkoHsA6zrOXrMMqXIXrCMqo4HpciEyKIXa-QRX1HUklBfX8cmeeyzK_KkWA4Pef71Rxp1FpPHvK-oPKnmP8P5tEo63YeILjoF2A73wCF3uM98y9QWznUpJTVEljj3le7DnKFvO4VXwHb2SFXZHVdiKOjxvmAPnfQ7WqQcGoi6J6PJSTsW88wE8JWkBZ1dU87EdPCCTx1DGUv62k25kE1Ky4bpEr5R8aTUVSOseimhKI4");'></div>
<button class="md:hidden">
<span class="material-symbols-outlined">menu</span>
</button>
</div>
    <x-block-div>

        <x-block-navigation 
            :navigation="[
                [
                    'title' => 'Changer nom',
                    'description' => 'Remplacer le nom ' . e($team->name) . '',
                    'route' => 'application.admin.configuration.name',
                ],
                [
                    'title' => 'Changer logo',
                    'description' => '',
                    'route' => 'application.admin.configuration.logo',
                    'image' => $team->profile_photo_path ? asset('storage/'.$team->profile_photo_path) : null,
                ],
            ]"
            :team="$team"
            backTitle="Retour à Administration"
            back="{{ route('application.admin.index', $team) }}"
        />
        
    </x-block-div>



</x-application-layout>