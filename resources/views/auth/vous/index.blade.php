<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between p-6 bg-slate-700 text-white">
            <h2 class="flex items-center">
                <span class="material-symbols-outlined text-4xl mr-2">home</span>
                Accueil
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button class="text-white hover:bg-red-600">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    Déconnexion
                </x-button>
            </form>
        </header>
    </x-slot>

    <main class="flex flex-1 justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl space-y-12">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-background-dark dark:text-background-light">Choose
                    your learning application</h1>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6">Available
                    applications</h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                        <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDqHxfIsC9BekzbtjN13pJEJZJLuXfkdzJ5i7yalgkgQGf17E_kK_mdxkJIiRAS-R4ya9SEeAU8_tOE94DGSlhUrWB2_lt6RtV0t1_hse3ps23TFH7C9VPDIAQuVLAJdu9N8DJb2IKdk-NlIvEIa8VdFjos5uEin8ajMVyrdqs7PB6N6Ogl8S_A-S7D9d9pFkanAZiH3HRWdPlWm08CMjDk7fOyq1tlb6s3CzX-DcbIj3vbSIfCslVWm5_V7mG62igLfzRz8sL6_50");'>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-semibold text-background-dark dark:text-background-light">EduStream</h3>
                            <p class="text-sm text-background-dark/60 dark:text-background-light/60">Your go-to platform
                                for interactive courses.</p>
                        </div>
                    </div>
                    <div
                        class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                        <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBLxVBzXTy39zVOBM8TVmHU27DSinMcCvGwBZYTh7a5oGUI537cQ0EBwe8EUYZUHgT4jiIIejxi3576H5pPsxS2_LCuTBWaphJpMPI8zu6n26jBncp4OrliirYIDdiyRfsWPahZWOO_F5NreQyq_FG5KxYlI9UDbH7hN9ZvC58um2irDSQ3hicBmccwygabZjkYGorIS4QLwF0-D0lg-FUVuc4P3xnEeWVHjeCLpdKdTjf0gS62ap2LfGWEeGJKKTjzjq5if812PxQ");'>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-semibold text-background-dark dark:text-background-light">SkillUp</h3>
                            <p class="text-sm text-background-dark/60 dark:text-background-light/60">Upskill with
                                industry-leading courses.</p>
                        </div>
                    </div>
                    <div
                        class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                        <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCbyt2-t3TwFfdjVqJ93rj8W24ETyQWCDzRzxwyo_9x7nB0sVciEiAAaTixTtcvXEiJlfrv6bTEZVGHS7c-gKEaNpLdm7N4mE-H86hSsKZQR3HmHK327vY_YQKQslV1DeKErM0lMAFbiMBy847A8nkT27cET0gEb0LVIjszaUYADNjm0ZEiFHjw6Z7zCWsck3TcSOvobiDwqcJTEdraoYQ4Xy1jBgTDcUPGVn4YkY_NLaVKDQfsyM4p5qD6BdnWhMyazePw5AfGfvQ");'>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-semibold text-background-dark dark:text-background-light">LearnNow</h3>
                            <p class="text-sm text-background-dark/60 dark:text-background-light/60">Learn at your own
                                pace with flexible courses.</p>
                        </div>
                    </div>
                    <div
                        class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                        <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCjFbmZZTSXNBlJm-LMAdMd0YpUHaTiKuKOFNvQgDXf3icGIMYkmDsloHvhBiuZM_aWPhLzMF-TW2RdCsMNZDtoqxrzVaVbjTi2MWbVEYg1DH5of4oD8AsxcJALhSSrj_IRSQBYzrQRrk0GataKh_r351AJG3QMUXiJLyb8KjBcyJaMJy_2F5akzUbh5N_M7fFYUPY727VlWhfvju3wwhhMbj8K6jSgVWQxVLOArqGKv3Cx2WpYIbG1XMmuINHCUB-QZAnQoE-e2Jo");'>
                        </div>
                        <div class="flex flex-col">
                            <h3 class="font-semibold text-background-dark dark:text-background-light">KnowledgeBase</h3>
                            <p class="text-sm text-background-dark/60 dark:text-background-light/60">Access a vast
                                library of educational resources.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6">Pending invitations
                </h2>
                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm dark:bg-background-dark/50">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 flex-shrink-0 rounded-lg bg-cover bg-center"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAf__oHvHTPwOTwkRfmRf1McbFv0g_1jMgoPtTwZseCxNVq6aSXAXLK-3ZJlQMbcxNpjRQgBK78kzjSDDq-dQgw60gHwxYpFk-CbidGf1MvQBzStF_5vXRasJhVQQubbMfxQWiihgOr5CLlfft4M-w7LKznp7UlentKoRoaMAHWpC-DhSXYhsYaetc6APktKe6PW3robH9y3jN0VAApOaAKuELNENF7B4jawDgNPzWhTZXC4MANg-KHhvnFOHd7PSy_Oix2ex-O1CI");'>
                            </div>
                            <div>
                                <p class="font-medium text-background-dark dark:text-background-light">Invite from
                                    DataCamp</p>
                                <p class="text-sm text-background-dark/60 dark:text-background-light/60">Introduction to
                                    Data Science</p>
                            </div>
                        </div>
                        <button
                            class="h-9 min-w-[84px] rounded bg-primary px-4 text-sm font-medium text-white shadow-sm hover:bg-primary/90">Accept</button>
                    </div>
                    <div
                        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm dark:bg-background-dark/50">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 flex-shrink-0 rounded-lg bg-cover bg-center"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDudUcNlPc8-a8u_qEAhDYVcSCIgdw-GMyIBjD_6vMFHE4fOXK2IMCTtlMwi_1JQDceW_0Qix_IkxzxRaKhqS2K3en2d4L4hNNqRdv-bR0oARBN0V4dX6jgYmS_gdVQBycPxFa1S4Zz3a1kItmCiWw3QMv377XS9SKaIu47NsTJIoPcjJrUF-ifWwGJZw_0cIgkEjpxY173plP9d4CnHQXs_LiDcMinOr_1d2Jw07rXiqDusbxZ2n_53TLoNyBLY-y3q5C_qacGDgw");'>
                            </div>
                            <div>
                                <p class="font-medium text-background-dark dark:text-background-light">Invite from
                                    Coursera</p>
                                <p class="text-sm text-background-dark/60 dark:text-background-light/60">Digital
                                    Marketing Fundamentals</p>
                            </div>
                        </div>
                        <button
                            class="h-9 min-w-[84px] rounded bg-primary px-4 text-sm font-medium text-white shadow-sm hover:bg-primary/90">Accept</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="py-12" style="display: none;">
        <x-block-div>
            @if ($items->count() > 0)
                <div class=" flex flex-wrap justify-center gap-6 pb-6 px-6">
                    @foreach ($items as $index => $item)
                        <form method="POST" action="{{ route('application.switch', $item) }}"
                            class="bg-slate-700 rounded-xl shadow-md group animate__animated animate__fadeInUp"
                            style="animation-delay: {{ $index * 0.15 }}s;">
                            @csrf
                            <button type="submit"
                                class="text-left focus:outline-none focus:ring-4 focus:ring-slate-500 focus:ring-offset-4 focus:ring-offset-white rounded-xl overflow-hidden transform transition-all duration-500 hover:shadow-3xl hover:shadow-slate-500/50 hover:ring-2 hover:ring-slate-300/50">
                                <div
                                    class="p-5 relative rounded-xl overflow-hidden shadow-2xl w-24 h-24 sm:w-36 sm:h-36 md:w-48 md:h-48 lg:w-60 lg:h-60">
                                    @if ($item->profile_photo_path)
                                        <img style="object-fit: scale-down;" src="{{ $item->profile_photo_url }}"
                                            alt="{{ $item->name }}"
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
                                        <h3 class="text-white font-semibold text-sm sm:text-base md:text-lg truncate">
                                            {{ $item->name }}
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
    </div>

</x-app-layout>
