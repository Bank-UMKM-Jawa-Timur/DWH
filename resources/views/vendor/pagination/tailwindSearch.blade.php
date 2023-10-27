<div class="flex justify-center">


    @if ($paginator->hasPages())
    <div class="sm:flex-1 sm:flex sm:items-center sm:justify-center lg:justify-between">
        <div>
            <p class="text-sm text-gray-700 leading-5">
                {!! __('Menampilkan') !!}
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                {!! __('sampai') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                {!! __('dari') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('data') !!}
            </p>
        </div>
    <div class="pagination">
        @if ($paginator->onFirstPage())
                <button class="btn-pagination is-unactive ml-2">Previous</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="ml-2  btn-pagination"
                    aria-label="{{ __('pagination.previous') }}">
                    Previous
                </a>
        @endif
    
            {{-- Pagination Elements --}}
        @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn-pagination lg:inline hidden ml-2 ">{{ $element }}</button>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn-pagination is-active ml-2">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn-pagination ml-2 lg:inline hidden"
                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
        @endforeach
            {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ml-2 btn-pagination"
                        aria-label="{{ __('pagination.previous') }}">
                        Next
                    </a>
        @else
            <button class="ml-2 btn-pagination is-unactive">Next</button>
        @endif
    
    </div>
    </div>
    @endif
    </div>