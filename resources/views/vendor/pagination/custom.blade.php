@if ($paginator->hasPages())
<nav class="paginacao-wrapper" aria-label="Paginação">
    {{-- Anterior --}}
    @if ($paginator->onFirstPage())
        <span class="paginacao-btn paginacao-btn--disabled">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="paginacao-btn">‹</a>
    @endif

    {{-- Números --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="paginacao-btn paginacao-btn--disabled">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="paginacao-btn paginacao-btn--ativo">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="paginacao-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Próximo --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="paginacao-btn">›</a>
    @else
        <span class="paginacao-btn paginacao-btn--disabled">›</span>
    @endif
</nav>
@endif
