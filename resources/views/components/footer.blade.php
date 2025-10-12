<footer class="mt-10 border-t bg-white px-6 py-4 text-sm text-gray-600">
  <div class="mx-auto flex max-w-7xl items-center justify-between">
    <div>
      
            <ul>
              <li><strong><a href="{{url('/')}}">{{  config('app.name') }}</a></strong></li>
        <li><a href="{{route('privacy')}}">Mentions légales</a></li>
        <li><a href="{{route('privacy')}}">Données personnelles</a></li>
        <li><a href="{{route('privacy')}}">Politique de confidentialité</a></li>
      </ul>
    </div>

    <div class="text-sm text-gray-600">{{  config('app.name') }} © {{ now()->year }}</div>
  </div>
</footer>
