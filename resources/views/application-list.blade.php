@props([
'title' => __('Sélectionnez votre application'),
'items' => Auth::user()->allTeams(),
'route' => 'application.switch'
])

<x-block-div>
    @if ($items->count() > 0)
    <div class=" flex flex-wrap justify-center gap-6 pb-6 px-6">
        @foreach ($items as $index => $item)
        <form method="POST" action="{{ route($route, $item) }}"
            class="bg-slate-700 rounded-xl shadow-md group animate__animated animate__fadeInUp"
            style="animation-delay: {{ $index * 0.15 }}s;">
            @csrf
            <button type="submit"
                class="text-left focus:outline-none focus:ring-4 focus:ring-slate-500 focus:ring-offset-4 focus:ring-offset-white rounded-xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl hover:shadow-slate-500/50 hover:ring-2 hover:ring-slate-300/50">
                <div
                    class="p-5 relative rounded-xl overflow-hidden shadow-2xl w-24 h-24 sm:w-36 sm:h-36 md:w-48 md:h-48 lg:w-60 lg:h-60">
                    @if($item->profile_photo_path)
                    <img style="object-fit: scale-down;" src="{{ $item->profile_photo_url }}" alt="{{ $item->name }}"
                        class="w-full h-full object-cover animate__animated animate__fadeIn transition-opacity duration-300">
                    @else
                    <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 text-slate-400 opacity-80 group-hover:text-slate-200 transition-colors duration-300"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif

                    <div
                        class="absolute bottom-0 left-0 right-0 p-2 sm:p-3 md:p-4 bg-gradient-to-t from-black/90 to-transparent">
                        <h3 class="text-white font-semibold text-sm sm:text-base md:text-lg truncate">{{ $item->name }}
                        </h3>
                    </div>
                </div>
            </button>
        </form>
        @endforeach
    </div>
    @else
    <div class="text-center py-16">
        <div
            class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 backdrop-blur-lg rounded-3xl p-16 max-w-2xl mx-auto border border-slate-200 shadow-xl">
            <div class="relative mb-8">
                <svg class="w-28 h-28 text-indigo-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd" />
                </svg>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-indigo-400/10 to-purple-400/10 rounded-full blur-2xl">
                </div>
            </div>
            <h3
                class="text-slate-800 text-2xl sm:text-3xl font-bold mb-6 bg-gradient-to-r from-slate-700 to-slate-600 bg-clip-text text-transparent">
                Aucune application trouvée
            </h3>
            <p class="text-slate-600 text-lg sm:text-xl mb-8 leading-relaxed">
                Vous recevrez un e-mail lorsque vous serez ajouté à une application.
            </p>
        </div>
    </div>
    @endif

    <livewire:invitations.pending-invitations />
</x-block-div>