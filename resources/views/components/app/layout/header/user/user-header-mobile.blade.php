<div class="py-6">
    <a href="{{ route('user.dashboard') }}"
        class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">{{ __('Mon compte') }}</a>

    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-red-900 hover:bg-red-50">{{ __('Deconnexion') }}</button>
    </form>
</div>
