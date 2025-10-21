<x-app-layout>
  @if(isset($header) && isset($team))
  <x-slot name="header">
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 border-b border-slate-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            @if($team->profile_photo_path)
            <div class="relative">
              @if(Auth::user()->hasTeamRole($team, 'admin'))
              <a href="{{
                                    route('application.admin.index', $team)
                                }}"
                class="w-12 h-12 bg-white bg-opacity-15 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white border-opacity-30 shadow-lg overflow-hidden">
                <img src="{{ asset('storage/'.$team->profile_photo_path) }}" alt="Logo {{ $team->name }}"
                  class="w-full h-full rounded-lg object-contain p-1" />
              </a>
              @endif
              <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white shadow-sm">
              </div>
            </div>
            @else
            <div
              class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <span class="material-symbols-outlined text-lg text-white">{{ $headerIcon ?? "school" }}</span>
            </div>
            @endif
            <div>
              <h2 class="text-2xl font-bold text-white">
                {{ $header }}
              </h2>
              @isset($subtitle)
              <p class="text-slate-300 mt-1">{{ $subtitle }}</p>
              @endisset
            </div>
          </div>
          @isset($headerActions)
          {{ $headerActions }}
          @else
          <div class="flex items-center space-x-3">
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                <span class="material-symbols-outlined mr-2">logout</span>
              </button>
            </form>
          </div>
          @endisset
        </div>
      </div>
    </div>
  </x-slot>
  @else
  <x-slot name="header">
    <div class="bg-gradient-to-br from-primary-900 via-slate-900 to-slate-800 relative overflow-hidden">
      <div class="absolute inset-0 magic-bg opacity-20"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-6">
            @if($team->profile_photo_path)
            <div class="relative">
              <div
                class="w-16 h-16 bg-white bg-opacity-15 backdrop-blur-sm rounded-2xl flex items-center justify-center border-2 border-white border-opacity-30 shadow-2xl overflow-hidden">
                <img src="{{ asset('storage/'.$team->profile_photo_path) }}" alt="Logo {{ $team->name }}"
                  class="w-full h-full rounded-xl object-contain p-1" />
              </div>
              <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white shadow-sm">
              </div>
            </div>
            @else
            <div
              class="w-16 h-16 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white border-opacity-20">
              <span class="material-symbols-outlined text-2xl text-white">dashboard</span>
            </div>
            @endif
            <div>
              <h1 class="text-3xl font-bold text-white">
                {{ config("app.name", "Application") }}
              </h1>
              <p class="text-slate-300 mt-1">
                Espace équipe professionnel
              </p>
            </div>
          </div>
          <div
            class="flex items-center space-x-3 text-slate-300 bg-white bg-opacity-5 backdrop-blur-sm rounded-xl px-4 py-2 border border-white border-opacity-10">
            <span class="material-symbols-outlined text-xl">business</span>
            <span class="font-medium">{{ $team->name }}</span>
          </div>
        </div>
      </div>
    </div>
  </x-slot>
  @endisset

  <x-error-display />

  {{-- BLOCK ÉLÉGANT --}}
  @isset($block)
  <x-slot name="block">
    <div
      class="bg-white bg-opacity-80 dark:bg-slate-800 dark:bg-opacity-80 backdrop-blur-sm mx-4 sm:mx-6 lg:mx-8 -mt-8 relative z-10 mb-8 border border-white border-opacity-20 dark:border-slate-700 dark:border-opacity-50 shadow-lg rounded-2xl transition-all duration-300 hover:shadow-xl">
      <div class="p-6">
        <div class="flex items-start space-x-4">
          <div
            class="w-10 h-10 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-800 dark:to-primary-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
            <span class="material-symbols-outlined text-primary-600 dark:text-primary-400">info</span>
          </div>
          <div class="flex-1">
            <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
              {{ $block }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </x-slot>
  @endisset @isset($slot)
  {{ $slot }}
  @endisset

  {{-- Zone d'erreurs / alertes modernisées --}}

  {{-- CONTENU PRINCIPAL AVEC ESPACE --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">@yield('content')</div>
</x-app-layout>