@props([
    'title',
    'subtitle' => null,
    'action' => null,
    'method' => 'POST',
    'submitLabel' => null,
    'buttonClasses' => 'w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 transform hover:scale-[1.02] active:scale-[0.98]',
    'showValidationErrors' => true,
    'statusMessage' => null,
    'remember' => false,
    'rememberId' => 'remember_me',
    'rememberName' => 'remember',
    'rememberLabel' => __('Remember me'),
    'rememberChecked' => false,
    'forgotPasswordUrl' => null,
    'forgotPasswordLabel' => __('Forgot password?'),
    'footerText' => null,
    'footerLinkText' => null,
    'footerLinkUrl' => null,
])

@php
    $httpMethod = strtoupper($method);
    $formMethod = in_array($httpMethod, ['GET', 'POST'], true) ? $httpMethod : 'POST';
    $shouldShowActionsRow = !isset($actions) && ($remember || $forgotPasswordUrl);
@endphp

<div {{ $attributes->class('min-h-screen flex flex-col sm:justify-center items-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-6 py-12') }}>
    <div class="w-full max-w-md">
        <!-- Application Logo -->
        <div class="text-center mb-8">
            <div class="relative inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-gray-900 via-gray-800 to-black rounded-3xl shadow-2xl border border-gray-700 mb-6 transform hover:scale-105 transition-all duration-500 group">
                <!-- Glow effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/30 via-purple-500/30 to-pink-500/30 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-4 shadow-inner border border-gray-600">
                    <img src="{{ asset('logo.png') }}"
                         alt="Logo de l'application"
                         class="w-20 h-20 object-contain drop-shadow-sm filter brightness-0 invert"
                         onerror="this.src='{{ asset('logo-dark.png') }}'">
                </div>
            </div>
            <div class="space-y-2">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-indigo-800 to-gray-900 bg-clip-text text-transparent">
                    {{ $title }}
                </h2>
                @if ($subtitle)
                    <p class="text-sm text-gray-600 font-medium">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-10">
                @if ($showValidationErrors)
                    <x-validation-errors class="mb-6" />
                @endif

                @isset($status)
                    <div class="mb-6">
                        {{ $status }}
                    </div>
                @elseif ($statusMessage)
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                        {{ $statusMessage }}
                    </div>
                @endif

                @if ($action)
                    <form method="{{ strtolower($formMethod) }}" action="{{ $action }}" class="space-y-6">
                        @if ($formMethod !== 'GET')
                            @csrf
                        @endif

                        @if ($httpMethod !== $formMethod)
                            @method($httpMethod)
                        @endif

                        @if (isset($fields))
                            {{ $fields }}
                        @else
                            {{ $slot }}
                        @endif

                        @if (isset($actions))
                            {{ $actions }}
                        @elseif ($shouldShowActionsRow)
                            <div class="flex items-center justify-between">
                                @if ($remember)
                                    <label for="{{ $rememberId }}" class="flex items-center">
                                        <x-checkbox
                                            id="{{ $rememberId }}"
                                            name="{{ $rememberName }}"
                                            :checked="$rememberChecked"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">{{ $rememberLabel }}</span>
                                    </label>
                                @endif

                                @if ($forgotPasswordUrl)
                                    <a
                                        class="text-sm text-indigo-600 hover:text-indigo-500 font-medium transition-colors duration-200"
                                        href="{{ $forgotPasswordUrl }}"
                                    >
                                        {{ $forgotPasswordLabel }}
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if ($submitLabel)
                            <div>
                                <x-button class="{{ $buttonClasses }}">
                                    {{ $submitLabel }}
                                </x-button>
                            </div>
                        @endif
                    </form>
                @else
                    <div class="space-y-6">
                        @if (isset($fields))
                            {{ $fields }}
                        @else
                            {{ $slot }}
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if (isset($footer))
            <div class="text-center mt-8">
                {{ $footer }}
            </div>
        @elseif ($footerText || ($footerLinkText && $footerLinkUrl))
            <div class="text-center mt-8 text-sm text-gray-600">
                @if ($footerText)
                    <span>{{ $footerText }}</span>
                @endif

                @if ($footerText && $footerLinkText && $footerLinkUrl)
                    <span> </span>
                @endif

                @if ($footerLinkText && $footerLinkUrl)
                    <a
                        href="{{ $footerLinkUrl }}"
                        class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200"
                    >
                        {{ $footerLinkText }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
