<div class="leaderboard-pml" x-data="leaderboardPml()" id="leaderboard-pml">
    <div class="leaderboard-pml__header">
        <span class="leaderboard-pml__eyebrow">Leaderboard Harian</span>
        <h2 class="leaderboard-pml__title">Leaderboard Petugas Pemeriksa Lapangan (PML)</h2>
        <p class="leaderboard-pml__description">
            Peringkat petugas berdasarkan jumlah reject + approve harian.
            Target harian = 5 × jumlah PCL yang dibawahi.
        </p>
    </div>

    @php
        $topThree = $pmlLeaderboardData->take(3);
        $rank4 = $pmlLeaderboardData->skip(3)->first();
        $rank5 = $pmlLeaderboardData->skip(4)->first();
    @endphp

    {{-- Podium Section --}}
    <section class="leaderboard-pml__podium">
        {{-- Rank 2 (Left) --}}
        @if($topThree->count() >= 2)
            @php $rank2 = $topThree[1]; @endphp
            <div class="leaderboard-pml__podium-card leaderboard-pml__podium-card--rank2">
                <div class="leaderboard-pml__rank-badge leaderboard-pml__rank-badge--silver">
                    <span>2</span>
                </div>
                <div class="leaderboard-pml__avatar leaderboard-pml__avatar--md">
                    {{ strtoupper(substr($rank2['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pml__info">
                    <h3 class="leaderboard-pml__name">{{ $rank2['name'] }}</h3>
                    <p class="leaderboard-pml__pcl-count">{{ $rank2['pcl_count'] }} PCL</p>
                </div>
                <div class="leaderboard-pml__stats">
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value">{{ $rank2['daily_reject'] }}</span>
                        <span class="leaderboard-pml__stat-label">Reject</span>
                    </div>
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value">{{ $rank2['daily_approve'] }}</span>
                        <span class="leaderboard-pml__stat-label">Approve</span>
                    </div>
                    <div class="leaderboard-pml__stat leaderboard-pml__stat--combined">
                        <span class="leaderboard-pml__stat-value">{{ $rank2['daily_combined'] }}</span>
                        <span class="leaderboard-pml__stat-label">Total</span>
                    </div>
                </div>
                <div class="leaderboard-pml__target-info">
                    <span class="leaderboard-pml__target-threshold">Target: ≥{{ $rank2['target_threshold'] }}</span>
                </div>
                <div class="leaderboard-pml__target-badge {{ $rank2['target_met'] ? 'leaderboard-pml__target-badge--met' : 'leaderboard-pml__target-badge--not-met' }}">
                    {{ $rank2['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif

        {{-- Rank 1 (Center - Largest) --}}
        @if($topThree->isNotEmpty())
            @php $rank1 = $topThree[0]; @endphp
            <div class="leaderboard-pml__podium-card leaderboard-pml__podium-card--rank1">
                <div class="leaderboard-pml__crown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5z"/>
                    </svg>
                </div>
                <div class="leaderboard-pml__rank-badge leaderboard-pml__rank-badge--gold">
                    <span>1</span>
                </div>
                <div class="leaderboard-pml__avatar leaderboard-pml__avatar--lg">
                    {{ strtoupper(substr($rank1['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pml__info">
                    <h3 class="leaderboard-pml__name leaderboard-pml__name--lg">{{ $rank1['name'] }}</h3>
                    <p class="leaderboard-pml__pcl-count leaderboard-pml__pcl-count--lg">{{ $rank1['pcl_count'] }} PCL</p>
                </div>
                <div class="leaderboard-pml__stats leaderboard-pml__stats--lg">
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value leaderboard-pml__stat-value--lg">{{ $rank1['daily_reject'] }}</span>
                        <span class="leaderboard-pml__stat-label">Reject</span>
                    </div>
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value leaderboard-pml__stat-value--lg">{{ $rank1['daily_approve'] }}</span>
                        <span class="leaderboard-pml__stat-label">Approve</span>
                    </div>
                    <div class="leaderboard-pml__stat leaderboard-pml__stat--combined">
                        <span class="leaderboard-pml__stat-value leaderboard-pml__stat-value--lg">{{ $rank1['daily_combined'] }}</span>
                        <span class="leaderboard-pml__stat-label">Total</span>
                    </div>
                </div>
                <div class="leaderboard-pml__target-info">
                    <span class="leaderboard-pml__target-threshold leaderboard-pml__target-threshold--lg">Target: ≥{{ $rank1['target_threshold'] }}</span>
                </div>
                <div class="leaderboard-pml__target-badge {{ $rank1['target_met'] ? 'leaderboard-pml__target-badge--met' : 'leaderboard-pml__target-badge--not-met' }}">
                    {{ $rank1['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif

        {{-- Rank 3 (Right) --}}
        @if($topThree->count() >= 3)
            @php $rank3 = $topThree[2]; @endphp
            <div class="leaderboard-pml__podium-card leaderboard-pml__podium-card--rank3">
                <div class="leaderboard-pml__rank-badge leaderboard-pml__rank-badge--bronze">
                    <span>3</span>
                </div>
                <div class="leaderboard-pml__avatar leaderboard-pml__avatar--md">
                    {{ strtoupper(substr($rank3['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pml__info">
                    <h3 class="leaderboard-pml__name">{{ $rank3['name'] }}</h3>
                    <p class="leaderboard-pml__pcl-count">{{ $rank3['pcl_count'] }} PCL</p>
                </div>
                <div class="leaderboard-pml__stats">
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value">{{ $rank3['daily_reject'] }}</span>
                        <span class="leaderboard-pml__stat-label">Reject</span>
                    </div>
                    <div class="leaderboard-pml__stat">
                        <span class="leaderboard-pml__stat-value">{{ $rank3['daily_approve'] }}</span>
                        <span class="leaderboard-pml__stat-label">Approve</span>
                    </div>
                    <div class="leaderboard-pml__stat leaderboard-pml__stat--combined">
                        <span class="leaderboard-pml__stat-value">{{ $rank3['daily_combined'] }}</span>
                        <span class="leaderboard-pml__stat-label">Total</span>
                    </div>
                </div>
                <div class="leaderboard-pml__target-info">
                    <span class="leaderboard-pml__target-threshold">Target: ≥{{ $rank3['target_threshold'] }}</span>
                </div>
                <div class="leaderboard-pml__target-badge {{ $rank3['target_met'] ? 'leaderboard-pml__target-badge--met' : 'leaderboard-pml__target-badge--not-met' }}">
                    {{ $rank3['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif
    </section>

    {{-- Rank 4-5 Cards --}}
    <section class="leaderboard-pml__runners-up">
        @if($rank4)
            <div class="leaderboard-pml__runner-card">
                <div class="leaderboard-pml__rank-badge leaderboard-pml__rank-badge--4">
                    <span>4</span>
                </div>
                <div class="leaderboard-pml__avatar leaderboard-pml__avatar--sm">
                    {{ strtoupper(substr($rank4['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pml__runner-info">
                    <h4 class="leaderboard-pml__runner-name">{{ $rank4['name'] }}</h4>
                    <span class="leaderboard-pml__runner-submit">{{ $rank4['daily_combined'] }} (R: {{ $rank4['daily_reject'] }} + A: {{ $rank4['daily_approve'] }})</span>
                </div>
            </div>
        @endif

        @if($rank5)
            <div class="leaderboard-pml__runner-card">
                <div class="leaderboard-pml__rank-badge leaderboard-pml__rank-badge--5">
                    <span>5</span>
                </div>
                <div class="leaderboard-pml__avatar leaderboard-pml__avatar--sm">
                    {{ strtoupper(substr($rank5['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pml__runner-info">
                    <h4 class="leaderboard-pml__runner-name">{{ $rank5['name'] }}</h4>
                    <span class="leaderboard-pml__runner-submit">{{ $rank5['daily_combined'] }} (R: {{ $rank5['daily_reject'] }} + A: {{ $rank5['daily_approve'] }})</span>
                </div>
            </div>
        @endif
    </section>

    {{-- Full Leaderboard Table --}}
    <section class="leaderboard-pml__table-section">
        <div class="leaderboard-pml__table-header">
            <h3 class="leaderboard-pml__table-title">Klasemen Lengkap</h3>
            <div class="leaderboard-pml__table-meta">
                <span class="leaderboard-pml__table-count">{{ $pmlLeaderboardDataPaginated->total() }} PML</span>
                @if($lastUpdate)
                    <span class="leaderboard-pml__table-last-update">
                        Kondisi {{ $lastUpdate->imported_at->setTimezone('Asia/Makassar')->format('j F Y H:i') }} WITA
                    </span>
                @endif
            </div>
        </div>

        {{-- Filter by Status Target --}}
        <div class="leaderboard-pml__filters">
            <div class="leaderboard-pml__filter-group">
                <label class="leaderboard-pml__filter-label">Filter Status:</label>
                <select id="pml-leaderboard-status-filter" class="leaderboard-pml__filter-select" x-model="statusFilter" @change="loadPage(1)">
                    <option value="">Semua</option>
                    <option value="met">Target Terpenuhi</option>
                    <option value="not_met">Belum Target</option>
                </select>
            </div>
        </div>

        <div class="leaderboard-pml__table-wrapper">
            <table class="leaderboard-pml__table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Nama PML</th>
                        <th>Jumlah PCL</th>
                        <th>Reject Harian</th>
                        <th>Approve Harian</th>
                        <th>Total (R+A)</th>
                        <th>Target</th>
                        <th>Status Target</th>
                    </tr>
                </thead>
                <tbody id="pml-leaderboard-table-body">
                    @foreach($pmlLeaderboardDataPaginated as $index => $pml)
                        @php
                            $globalRank = ($pmlLeaderboardDataPaginated->currentPage() - 1) * $pmlLeaderboardDataPaginated->perPage() + $index + 1;
                        @endphp
                        <tr class="{{ $pml['target_met'] ? 'leaderboard-pml__row--met' : 'leaderboard-pml__row--not-met' }}">
                            <td class="leaderboard-pml__rank-cell">
                                <span class="leaderboard-pml__rank-number">{{ $globalRank }}</span>
                            </td>
                            <td class="leaderboard-pml__name-cell">
                                <div class="leaderboard-pml__name-cell-content">
                                    <div class="leaderboard-pml__avatar leaderboard-pml__avatar--xs">
                                        {{ strtoupper(substr($pml['name'], 0, 1)) }}
                                    </div>
                                    <span>{{ $pml['name'] }}</span>
                                </div>
                            </td>
                            <td class="leaderboard-pml__pcl-cell">{{ $pml['pcl_count'] }}</td>
                            <td class="leaderboard-pml__reject-cell">{{ $pml['daily_reject'] }}</td>
                            <td class="leaderboard-pml__approve-cell">{{ $pml['daily_approve'] }}</td>
                            <td class="leaderboard-pml__combined-cell">
                                <span class="leaderboard-pml__combined-value">{{ $pml['daily_combined'] }}</span>
                            </td>
                            <td class="leaderboard-pml__target-cell">
                                <span class="leaderboard-pml__target-badge-inline">≥{{ $pml['target_threshold'] }}</span>
                            </td>
                            <td class="leaderboard-pml__status-cell">
                                <span class="leaderboard-pml__status-badge {{ $pml['target_met'] ? 'leaderboard-pml__status-badge--met' : 'leaderboard-pml__status-badge--not-met' }}">
                                    {{ $pml['target_met'] ? 'Terpenuhi' : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="pml-leaderboard-pagination-wrapper">
            @include('components.partials.pml-leaderboard-pagination', ['pmlLeaderboardPaginated' => $pmlLeaderboardDataPaginated])
        </div>
    </section>
</div>

<script>
    function leaderboardPml() {
        return {
            currentPage: {{ $pmlLeaderboardDataPaginated->currentPage() }},
            statusFilter: '',

            initPagination() {
                const paginationWrapper = document.getElementById('pml-leaderboard-pagination-wrapper');
                if (paginationWrapper) {
                    paginationWrapper.addEventListener('click', (e) => {
                        const btn = e.target.closest('[data-page]');
                        if (!btn) return;

                        e.preventDefault();
                        const page = btn.dataset.page;
                        this.loadPage(page);
                    });
                }
            },

            loadPage(page) {
                const tableBody = document.getElementById('pml-leaderboard-table-body');
                const paginationWrapper = document.getElementById('pml-leaderboard-pagination-wrapper');
                const statusFilter = this.statusFilter;

                let url = `/pml-leaderboard-page?page=${page}`;
                if (statusFilter) {
                    url += `&status=${statusFilter}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = data.html;
                        paginationWrapper.innerHTML = data.pagination;
                        this.currentPage = page;
                        this.initPagination();
                    })
                    .catch(error => console.error('Error loading page:', error));
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const paginationWrapper = document.getElementById('pml-leaderboard-pagination-wrapper');
        if (paginationWrapper) {
            paginationWrapper.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-page]');
                if (!btn) return;

                e.preventDefault();
                const page = btn.dataset.page;
                const tableBody = document.getElementById('pml-leaderboard-table-body');
                const paginationWrapperEl = document.getElementById('pml-leaderboard-pagination-wrapper');
                const statusFilter = document.getElementById('pml-leaderboard-status-filter')?.value || '';

                let url = `/pml-leaderboard-page?page=${page}`;
                if (statusFilter) {
                    url += `&status=${statusFilter}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = data.html;
                        paginationWrapperEl.innerHTML = data.pagination;
                    })
                    .catch(error => console.error('Error loading page:', error));
            });
        }
    });
</script>
