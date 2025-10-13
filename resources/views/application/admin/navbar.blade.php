    <x-slot name="navbar">
        <li>
            <a href="{{ route('application.admin.index', $team) }}">{{ __('Accueil') }}</a>
        </li>
        <li>
            <a href="{{ route('application.admin.users', $team) }}">{{ __('Utilisateurs') }}</a>
        </li>
        <li>
            <a href="{{  route('application.admin.config', $team) }}">{{ __('Paramètres') }}</a>
        </li>
    </x-slot>