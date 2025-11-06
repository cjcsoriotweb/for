@component('mail::message')

<p align="center" style="margin: 0 0 16px;">
  <img src="https://evolubat.goodview.fr/logo.png"
       alt="EvolubatAcademy logo"
       width="80" height="80"
       style="display:inline-block; width:80px; height:80px;">
</p>

{!! __('Vous êtes invité(e) à rejoindre <b>:team</b> !', ['team' => e($invitation->team->name)]) !!}

{{ __('Vous pouvez vous inscrire ou créer un compte pour suivre vos formations :') }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
@component('mail::button', ['url' => route('register')])
{{ __("S'inscrire") }}
@endcomponent

@component('mail::button', ['url' => route('login')])
{{ __("Se connecter") }}
@endcomponent
@endif

**{{ __('Ne tardez pas ! Inscrivez-vous dès maintenant et commencez vos formations sans attendre afin de ne rien manquer.') }}**

{{ __("Si vous n’êtes pas le destinataire de ce message, vous pouvez simplement l’ignorer.") }}
@endcomponent
