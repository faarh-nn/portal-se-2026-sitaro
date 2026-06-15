<div class="leaderboard-pagination">
    <div class="leaderboard-pagination__info">
        Menampilkan {{ $leaderboardPaginated->firstItem() ?? 0 }} - {{ $leaderboardPaginated->lastItem() ?? 0 }} dari {{ $leaderboardPaginated->total() }} PCL
    </div>
    <div class="leaderboard-pagination__controls">
        @if ($leaderboardPaginated->onFirstPage())
            <span class="leaderboard-pagination__btn leaderboard-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        @else
            <button type="button" class="leaderboard-pagination__btn" data-page="{{ $leaderboardPaginated->currentPage() - 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        @endif

        @php
            $currentPage = $leaderboardPaginated->currentPage();
            $lastPage = $leaderboardPaginated->lastPage();

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
                <span class="leaderboard-pagination__ellipsis">...</span>
            @endif

            @if ($page == $currentPage)
                <span class="leaderboard-pagination__page leaderboard-pagination__page--active">{{ $page }}</span>
            @else
                <button type="button" class="leaderboard-pagination__page" data-page="{{ $page }}">{{ $page }}</button>
            @endif
        @endforeach

        @if ($leaderboardPaginated->hasMorePages())
            <button type="button" class="leaderboard-pagination__btn" data-page="{{ $leaderboardPaginated->currentPage() + 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        @else
            <span class="leaderboard-pagination__btn leaderboard-pagination__btn--disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        @endif
    </div>
</div>
