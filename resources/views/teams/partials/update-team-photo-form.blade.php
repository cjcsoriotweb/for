<div class="hidden sm:block">
    <div class="py-8">
        <div class="border-t border-gray-200"></div>
    </div>
</div>
<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Logo de votre application</h3>

            
            </div>

            <div class="px-4 sm:px-0">

            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <section
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <img class="h-20 w-20 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-700"
                            src="{{ $team->profile_photo_url }}" alt="Photo de l'équipe">
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Photo de l'équipe</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mettez à jour la photo de votre équipe</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <form method="POST" action="{{ route('application.admin.photo.update', $team) }}" enctype="multipart/form-data"
                        class="flex-1">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input type="file" name="photo" accept="image/*" required id="photo-upload"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <label for="photo-upload" class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium
                                  bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300
                                  border border-gray-300 dark:border-gray-600
                                  hover:bg-gray-200 dark:hover:bg-gray-600
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500/50
                                  transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4">
                                        </path>
                                    </svg>
                                    Choisir une image
                                </label>
                            </div>
                            <x-primary-button class="flex-shrink-0">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Mettre à jour
                            </x-primary-button>
                        </div>
                    </form>

                    @if($team->profile_photo_path)
                    <form method="POST" action="{{ route('application.admin.photo.destroy', $team) }}" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Supprimer
                        </x-danger-button>
                    </form>
                    @endif
                </div>

                <div class="space-y-2">
                    @error('photo')
                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror

                    @if (session('status') === 'team-photo-updated')
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Photo mise à jour avec succès.
                    </div>
                    @endif

                    @if (session('status') === 'team-photo-removed')
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Photo supprimée avec succès.
                    </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>