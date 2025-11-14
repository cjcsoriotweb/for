@if (Auth::user()->superadmin)
    <button type="button" command="--toggle" commandfor="products"
        class="flex w-full items-center justify-between rounded-lg py-2 pr-3.5 pl-3 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
        {{ __('Super-Admin') }}
        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
            class="size-5 flex-none in-aria-expanded:rotate-180">
            <path
                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </button>

    <el-disclosure id="products" hidden class="mt-2 block space-y-2">
        <a href="{{ route('superadmin.overview') }}"
            class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ __('Accueil') }}</a>
        <a href="{{ route('superadmin.support.index') }}"
            class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ __('Tickets') }}</a>
        <a href="{{ route('superadmin.users.index') }}"
            class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ __('Utilisateurs') }}</a>
        <a href="{{ route('superadmin.teams.index') }}"
            class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ __('Equipes') }}</a>
    </el-disclosure>
@endif
