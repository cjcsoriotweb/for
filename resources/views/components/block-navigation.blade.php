<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

    @if($back)
    <x-button-block titre="Retour" description="{{ url()->previous() }}" button="<---" url="{{ url()->previous() }}" />
    @endif

    <x-button-block titre="Changez le nom de votre application" description="...."
        url="{{ route('application.admin.configuration.name', ['team'=>$team]) }}" />
    <x-button-block titre="Changez le logo de votre application" description="...."
        url="{{ route('application.admin.configuration.name', ['team'=>$team]) }}" />
</section>