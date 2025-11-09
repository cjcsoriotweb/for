@component('mail::message')
# {{ __('Team Invitation') }}

{{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name ?? '']) }}

{{ __('If you do not have an account, you can create one after accepting this invitation.') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}

{{ __('Regards') }},
{{ config('app.name') }}
@endcomponent

