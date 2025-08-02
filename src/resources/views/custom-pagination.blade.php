@if ($paginator->hasPages())
<nav>
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <span class="page-link disabled">&laquo;</span>
        @else
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <span class="page-link disabled">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <span class="page-link current">{{ $page }}</span>
        @else
        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
        <span class="page-link disabled">&raquo;</span>
        @endif
    </div>
</nav>
@endif