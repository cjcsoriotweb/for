@component('mail::message')
{{ __('Vous êtes invité(e) à rejoindre <b>:team !</b>', ['team' => $invitation->team->name]) }}
{{ __('Vous pouvez vous inscrire ou créer un compte pour suivre vos formations:') }}


<div style="flex;">
@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))

    @component('mail::button', ['url' => route('register')])
    {{ __("S'inscrire") }}
    @endcomponent

    @component('mail::button', ['url' => route('login')])
    {{ __("Se connecter") }}
    @endcomponent

@endif
</div>

<b>{{__('Ne tardez pas ! Inscrivez-vous dès maintenant et commencez vos formations sans attendre pour ne rien manquer.')}}</b>


{{ __("Si vous n’êtes pas le destinataire de ce message, vous pouvez simplement l’ignorer.") }}
@endcomponent
