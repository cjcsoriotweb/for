@props(['items'])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($items as $index => $item)
            <li @class(['inline-flex items-center' => $index === 0])>
                @if($index > 0)
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                @endif

                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-sm text-gray-700 hover:text-indigo-600 @if($index > 0) ml-1 @endif">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="text-sm @if($loop->last) text-gray-500 @else text-gray-700 @endif @if($index > 0) ml-1 @endif">
                        {{ $item['label'] }}
                    </span>
                @endif

                @if($index > 0)
                    </div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
