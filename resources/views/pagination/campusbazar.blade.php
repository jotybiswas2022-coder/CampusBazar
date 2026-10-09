@if ($paginator->hasPages())
    <nav aria-label="Pagination">
        <ul class="pager">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span aria-hidden="true">‹</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">‹</a></li>
            @endif

            {{-- Page links --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">›</a></li>
            @else
                <li class="disabled"><span aria-hidden="true">›</span></li>
            @endif
        </ul>
    </nav>
@endif
