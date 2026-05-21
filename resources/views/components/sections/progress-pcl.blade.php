<div class="pcl-section">
    <div class="pcl-section__header">
        <span class="pcl-section__eyebrow">Monitoring PCL</span>
        <h2 class="pcl-section__title">Progress per PCL (Petugas Pencacah Lapangan)</h2>
        <p class="pcl-section__description">
            Monitoring progress pekerjaan lapangan setiap PCL berdasarkan status objek kuesioner.
        </p>
    </div>

    <section class="pcl-section__container">
        <div class="pcl-stats-row">
            <div class="pcl-stat-card pcl-stat-card--open">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['open']) }}</span>
                    <span class="pcl-stat-card__label">Open</span>
                </div>
            </div>

            <div class="pcl-stat-card pcl-stat-card--submit">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['submit']) }}</span>
                    <span class="pcl-stat-card__label">Submit</span>
                </div>
            </div>

            <div class="pcl-stat-card pcl-stat-card--reject">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['reject']) }}</span>
                    <span class="pcl-stat-card__label">Reject</span>
                </div>
            </div>

            <div class="pcl-stat-card pcl-stat-card--pending">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['pending']) }}</span>
                    <span class="pcl-stat-card__label">Pending</span>
                </div>
            </div>

            <div class="pcl-stat-card pcl-stat-card--approved">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['approved']) }}</span>
                    <span class="pcl-stat-card__label">Approved</span>
                </div>
            </div>
        </div>

        <div class="pcl-table-section">
            <div class="pcl-table-wrapper">
                <table class="pcl-table" id="pcl-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama PCL</th>
                            <th>Open</th>
                            <th>Submit</th>
                            <th>Reject</th>
                            <th>Pending</th>
                            <th>Approved (Progress)</th>
                        </tr>
                    </thead>
                    <tbody id="pcl-table-body">
                        @foreach($pclDataPaginated as $index => $pcl)
                            @php
                                $pclBarColor = $pcl['progress'] == 100 ? '#10B981' : ($pcl['progress'] >= 71 ? '#00a6fb' : ($pcl['progress'] >= 41 ? '#F59E0B' : '#EF4444'));
                                $rowNumber = ($pclDataPaginated->currentPage() - 1) * $pclDataPaginated->perPage() + $index + 1;
                            @endphp
                            <tr>
                                <td class="pcl-table__no">{{ $rowNumber }}</td>
                                <td class="pcl-table__name">{{ $pcl['name'] }}</td>
                                <td class="pcl-table__open">{{ $pcl['open'] }}</td>
                                <td class="pcl-table__submit">{{ $pcl['submit'] }}</td>
                                <td class="pcl-table__reject">{{ $pcl['reject'] }}</td>
                                <td class="pcl-table__pending">{{ $pcl['pending'] }}</td>
                                <td class="pcl-table__approved">
                                    <div class="pcl-progress">
                                        <div class="pcl-progress__bar">
                                            <div class="pcl-progress__fill" style="width: {{ $pcl['progress'] }}%; background-color: {{ $pclBarColor }}"></div>
                                        </div>
                                        <span class="pcl-progress__value">{{ $pcl['approved'] }}/{{ $pcl['target'] }} ({{ $pcl['progress'] }}%)</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="pcl-pagination-wrapper">
                @include('components.partials.pcl-table-pagination', ['pclPaginated' => $pclDataPaginated])
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('pcl-table-body');
    const paginationWrapper = document.getElementById('pcl-pagination-wrapper');

    function loadPclTablePage(page) {
        const url = `/pcl-table-page?page=${page}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = data.html;
                paginationWrapper.innerHTML = data.pagination;
            })
            .catch(error => console.error('Error loading page:', error));
    }

    paginationWrapper.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-page]');
        if (!btn) return;

        e.preventDefault();
        const page = btn.dataset.page;
        loadPclTablePage(page);
    });
});
</script>