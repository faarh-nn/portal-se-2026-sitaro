<div class="pcl-table-pagination">
    <div class="pcl-table-pagination__info">
        Menampilkan {{ $pclPaginated->firstItem() ?? 0 }} - {{ $pclPaginated->lastItem() ?? 0 }} dari {{ $pclPaginated->total() }} PCL
    </div>
    <div class="pcl-table-pagination__controls">
        @if ($pclPaginated->onFirstPage())
            <span class="pcl-table-pagination__btn pcl-table-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        @else
            <button type="button" class="pcl-table-pagination__btn" data-page="{{ $pclPaginated->currentPage() - 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        @endif

        @php
            $currentPage = $pclPaginated->currentPage();
            $lastPage = $pclPaginated->lastPage();

            $pages = [];
            $pages[] = 1;

            if ($currentPage > 3 && $lastPage > 4) {
                $hasLeftEllipsis = true;
            } else {
                $hasLeftEllipsis = false;
            }

            $startAround = max(2, $currentPage - 1);
            $endAround = min($lastPage - 1, $currentPage + 1);

            for ($i = $startAround; $i <= $endAround; $i++) {
                if ($i > 1 && $i < $lastPage) {
                    $pages[] = $i;
                }
            }

            if ($currentPage < $lastPage - 2 && $lastPage > 4) {
                $hasRightEllipsis = true;
            } else {
                $hasRightEllipsis = false;
            }

            if ($lastPage > 1) {
                $pages[] = $lastPage;
            }

            $pages = array_unique($pages);
            sort($pages);
        @endphp

        @foreach ($pages as $index => $page)
            @if ($index > 0 && $page - $pages[$index - 1] > 1)
                <span class="pcl-table-pagination__ellipsis">...</span>
            @endif

            @if ($page == $currentPage)
                <span class="pcl-table-pagination__page pcl-table-pagination__page--active">{{ $page }}</span>
            @else
                <button type="button" class="pcl-table-pagination__page" data-page="{{ $page }}">{{ $page }}</button>
            @endif
        @endforeach

        @if ($pclPaginated->hasMorePages())
            <button type="button" class="pcl-table-pagination__btn" data-page="{{ $pclPaginated->currentPage() + 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        @else
            <span class="pcl-table-pagination__btn pcl-table-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        @endif
    </div>
</div>