<form method="POST" action="{{ route('password.email') }}" {{ $attributes }}>
    @csrf

    <div class="block">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input
            id="email"
            class="block mt-1 w-full"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            autocomplete="username"
        />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-button>
            {{ __('Email Password Reset Link') }}
        </x-button>
    </div>
</form>
