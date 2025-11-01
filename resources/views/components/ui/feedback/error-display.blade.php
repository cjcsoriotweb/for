@if(session('success'))
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 m-2">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center" role="alert">
        {!! session('success') !!}
    </div>
</div>
@endif

@if(session('error'))
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 m-2">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center" role="alert">
        {!! session('error') !!}
    </div>
</div>
@endif