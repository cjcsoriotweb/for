                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="ml-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">{{ __('Deconnexion')}}</button>
                </form>