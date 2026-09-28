@if ($paginator->hasPages())
    <nav class="admin-pager" role="navigation" aria-label="Pagination Navigation">
        {{-- First / Previous --}}
        @if ($paginator->onFirstPage())
            <span class="admin-pager__btn is-disabled" aria-disabled="true">««</span>
            <span class="admin-pager__btn is-disabled" aria-disabled="true">‹</span>
        @else
            <a class="admin-pager__btn" href="{{ $paginator->url(1) }}" aria-label="First page">««</a>
            <a class="admin-pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">‹</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="admin-pager__dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="admin-pager__btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="admin-pager__btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next / Last --}}
        @if ($paginator->hasMorePages())
            <a class="admin-pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">›</a>
            <a class="admin-pager__btn" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last page">»»</a>
        @else
            <span class="admin-pager__btn is-disabled" aria-disabled="true">›</span>
            <span class="admin-pager__btn is-disabled" aria-disabled="true">»»</span>
        @endif
    </nav>
@endif

