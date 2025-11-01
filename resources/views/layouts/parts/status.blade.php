    {{-- Flash status éventuel --}}
    @if (session('status'))
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
      <div class="rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/40 dark:text-green-100">
        {{ session("status") }}
      </div>
    </div>
    @endif