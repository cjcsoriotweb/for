@component('mail::message')
{{ __('Vous êtes invité à rejoindre :team !', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __("Vous pouvez vous inscrire ou creer un compte pour suivre vos formations :") }}

@component('mail::button', ['url' => route('register')])
{{ __("S'inscrire") }}
@endcomponent

@component('mail::button', ['url' => route('login')])
{{ __("Se connecter") }}
@endcomponent

@endif




{{ __("Si ce mail ne vous est pas déstiné vous pouvez l'ignorer.") }}
@endcomponent
