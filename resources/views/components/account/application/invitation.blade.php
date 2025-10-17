@if(isset($invitations_pending)) @if ($invitations_pending->count() > 0)
<!-- Pending Invitations Section -->
<div class="mb-16">
    <div class="mb-8 text-center">
        <h2 class="mb-4 text-3xl font-bold text-slate-900 dark:text-white">
            {{ __("Invitations en attente") }}
        </h2>
        <p class="text-slate-600 dark:text-slate-300">
            {{
                __(
                    "Vous avez reçu de nouvelles invitations à rejoindre des organismes"
                )
            }}
        </p>
    </div>

    <div class="mx-auto max-w-2xl space-y-4">
        @foreach ($invitations_pending as $invitation)
        <div
            class="group relative overflow-hidden rounded-2xl bg-white p-6 ring-1 ring-slate-200 transition-all duration-300 hover:shadow-lg dark:bg-slate-800/50 dark:ring-slate-700"
        >
            <!-- Background decoration -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-50 to-green-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100 dark:from-emerald-900/20 dark:to-green-900/20"
            ></div>

            <div class="relative flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Team logo -->
                    <div
                        class="flex h-14 w-14 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600"
                    >
                        @if($invitation->team->profile_photo_url)
                        <img
                            src="{{ $invitation->team->profile_photo_url }}"
                            alt="{{ $invitation->team->name }}"
                            class="h-full w-full"
                            style="object-fit: scale-down"
                        />
                        @else
                        <span
                            class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300"
                            >business</span
                        >
                        @endif
                    </div>

                    <!-- Invitation info -->
                    <div>
                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            {{ $invitation->team->name }}
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __("vous invite à rejoindre leur organisme") }}
                        </p>
                    </div>
                </div>

                <!-- Accept button -->
                <form
                    method="POST"
                    action="{{ route('vous.invitation.accept', $invitation->id) }}"
                    class="flex items-center gap-2"
                >
                    @csrf @method('PATCH')
                    <button
                        type="submit"
                        class="group/btn flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:from-emerald-600 hover:to-green-600 hover:scale-105"
                    >
                        <span
                            class="material-symbols-outlined text-base transition-transform group-hover/btn:scale-110"
                            >check</span
                        >
                        {{ __("Accepter") }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif @endif
