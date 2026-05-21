<div class="gmaps-table-pagination">
    <div class="gmaps-table-pagination__info">
        Menampilkan {{ $allUsahaGmaps->firstItem() ?? 0 }} - {{ $allUsahaGmaps->lastItem() ?? 0 }} dari {{ $allUsahaGmaps->total() }} usaha
    </div>
    <div class="gmaps-table-pagination__controls">
        @if ($allUsahaGmaps->onFirstPage())
            <span class="gmaps-table-pagination__btn gmaps-table-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        @else
            <button type="button" class="gmaps-table-pagination__btn" data-page="{{ $allUsahaGmaps->currentPage() - 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        @endif

        @php
            $currentPage = $allUsahaGmaps->currentPage();
            $lastPage = $allUsahaGmaps->lastPage();

            // Build page numbers to display
            $pages = [];

            // Always show page 1
            $pages[] = 1;

            // Add ellipsis after page 1 if needed
            if ($currentPage > 3 && $lastPage > 4) {
                $hasLeftEllipsis = true;
            } else {
                $hasLeftEllipsis = false;
            }

            // Add pages around current page
            $startAround = max(2, $currentPage - 1);
            $endAround = min($lastPage - 1, $currentPage + 1);

            // Add middle pages
            for ($i = $startAround; $i <= $endAround; $i++) {
                if ($i > 1 && $i < $lastPage) {
                    $pages[] = $i;
                }
            }

            // Add ellipsis before last page if needed
            if ($currentPage < $lastPage - 2 && $lastPage > 4) {
                $hasRightEllipsis = true;
            } else {
                $hasRightEllipsis = false;
            }

            // Always show last page (if more than 1)
            if ($lastPage > 1) {
                $pages[] = $lastPage;
            }

            // Remove duplicates and sort
            $pages = array_unique($pages);
            sort($pages);
        @endphp

        @foreach ($pages as $index => $page)
            @if ($index > 0 && $page - $pages[$index - 1] > 1)
                <span class="gmaps-table-pagination__ellipsis">...</span>
            @endif

            @if ($page == $currentPage)
                <span class="gmaps-table-pagination__page gmaps-table-pagination__page--active">{{ $page }}</span>
            @else
                <button type="button" class="gmaps-table-pagination__page" data-page="{{ $page }}">{{ $page }}</button>
            @endif
        @endforeach

        @if ($allUsahaGmaps->hasMorePages())
            <button type="button" class="gmaps-table-pagination__btn" data-page="{{ $allUsahaGmaps->currentPage() + 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        @else
            <span class="gmaps-table-pagination__btn gmaps-table-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        @endif
    </div>
</div>