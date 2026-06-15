<div class="pml-leaderboard-pagination">
    <div class="pml-leaderboard-pagination__info">
        Menampilkan {{ $pmlLeaderboardPaginated->firstItem() ?? 0 }} - {{ $pmlLeaderboardPaginated->lastItem() ?? 0 }} dari {{ $pmlLeaderboardPaginated->total() }} PML
    </div>
    <div class="pml-leaderboard-pagination__controls">
        @if ($pmlLeaderboardPaginated->onFirstPage())
            <span class="pml-leaderboard-pagination__btn pml-leaderboard-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        @else
            <button type="button" class="pml-leaderboard-pagination__btn" data-page="{{ $pmlLeaderboardPaginated->currentPage() - 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        @endif

        @php
            $currentPage = $pmlLeaderboardPaginated->currentPage();
            $lastPage = $pmlLeaderboardPaginated->lastPage();

            $pages = [];
            $pages[] = 1;

            if ($currentPage > 3 && $lastPage > 4) {
                $hasLeftEllipsis = true;
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
            }

            if ($lastPage > 1) {
                $pages[] = $lastPage;
            }

            $pages = array_unique($pages);
            sort($pages);
        @endphp

        @foreach ($pages as $index => $page)
            @if ($index > 0 && $page - $pages[$index - 1] > 1)
                <span class="pml-leaderboard-pagination__ellipsis">...</span>
            @endif

            @if ($page == $currentPage)
                <span class="pml-leaderboard-pagination__page pml-leaderboard-pagination__page--active">{{ $page }}</span>
            @else
                <button type="button" class="pml-leaderboard-pagination__page" data-page="{{ $page }}">{{ $page }}</button>
            @endif
        @endforeach

        @if ($pmlLeaderboardPaginated->hasMorePages())
            <button type="button" class="pml-leaderboard-pagination__btn" data-page="{{ $pmlLeaderboardPaginated->currentPage() + 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        @else
            <span class="pml-leaderboard-pagination__btn pml-leaderboard-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        @endif
    </div>
</div>
