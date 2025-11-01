<form method="POST" action="{{ route('password.confirm') }}" {{ $attributes }}>
    @csrf

    <div>
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input
            id="password"
            class="block mt-1 w-full"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            autofocus
        />
    </div>

    <div class="flex justify-end mt-4">
        <x-button class="ms-4">
            {{ __('Confirm') }}
        </x-button>
    </div>
</form>
