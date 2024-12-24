@if ($paginator->hasPages())
<nav class="pagination_container">
    <ul class="pagination">
        {{-- 最初のページへのリンクを現在のページが2の場合は表示しない --}}
        @if(!$paginator->onFirstPage() && $paginator->currentPage() != 2)
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link bg-black" href="{{ $paginator->url(1) }}">&laquo;</a>
        </li>
        @endif


        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link bg-black" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
        </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $showPages = 10; // 最大表示ページ数
        $startPage = max($currentPage - 5, 1);
        $endPage = min($currentPage + 4, $lastPage);
        @endphp

        @foreach ($element as $page => $url)
        @if ($page == $currentPage)
        <li class="page-item active" aria-current="page"><span class="page-link bg-gradation">{{ $page }}</span></li>
        @elseif ($page >= $startPage && $page <= $endPage) <li class="page-item"><a class="page-link" style="color: black;" href="{{ $url }}">{{ $page }}</a></li>
            @endif
            @endforeach
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link bg-black" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
            @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
            @endif

            {{-- Last Page Link --}}
            @if($paginator->lastPage() >= 3)
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                {{-- 現在のページが最後のページでない場合にリンクを表示 --}}
                @if($paginator->currentPage() != $paginator->lastPage())
                <a class="page-link bg-black" href="{{ $paginator->url($paginator->lastPage()) }}">&raquo;</a>
                @else
                {{-- 現在のページが最後のページの場合はリンクを非活性化（または非表示にする） --}}
                @endif
            </li>
            @endif
    </ul>
</nav>
@endif