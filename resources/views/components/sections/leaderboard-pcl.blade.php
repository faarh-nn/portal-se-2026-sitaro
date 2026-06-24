<div class="leaderboard-pcl" x-data="leaderboardPcl()" id="leaderboard-pcl">
    <div class="leaderboard-pcl__header">
        <span class="leaderboard-pcl__eyebrow">Leaderboard Harian</span>
        <h2 class="leaderboard-pcl__title">Leaderboard Pencacah Lapangan (PCL)</h2>
        <p class="leaderboard-pcl__description">
            Peringkat petugas berdasarkan jumlah submit harian. Target harian minimum adalah 10 submit.
        </p>
        @if($leaderboardLastUpdate)
            @php
                $importedAt = $leaderboardLastUpdate->setTimezone('Asia/Makassar');
                $hour = (int) $importedAt->format('H');
                $importedAtYesterday = $importedAt->copy()->subDay();
                $dayName = '';
                if ($hour >= 5 && $hour < 8) {
                    // 5 pagi WITA - 8 pagi WITA → nama hari kemarin
                    $dayName = match ($importedAtYesterday->format('l')) {
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                        default => $importedAtYesterday->format('j F Y'),
                    };
                } elseif ($hour >= 19 && $hour <= 21) {
                    // 7 malam WITA - 9 malam WITA → nama hari sesuai last update
                    $dayName = match ($importedAt->format('l')) {
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                        default => $importedAt->format('j F Y'),
                    };
                } else {
                    // Fallback: tampilkan nama hari sesuai last update untuk jam lain
                    $dayName = match ($importedAt->format('l')) {
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                        default => $importedAt->format('j F Y'),
                    };
                }
                $conditionText = $importedAt->format('j F Y H:i') . ' WITA';
            @endphp
            <div class="leaderboard-pcl__last-update-badge-wrapper" x-data="{ showTooltip: false }">
                <div class="leaderboard-pcl__last-update-badge" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                    Leaderboard {{ $dayName }} kondisi {{ $conditionText }}
                </div>
                <div class="leaderboard-pcl__tooltip"
                     x-show="showTooltip"
                     x-transition:enter="leaderboard-transition-enter"
                     x-transition:enter-start="leaderboard-transition-enter-start"
                     x-transition:enter-end="leaderboard-transition-enter-end"
                     x-transition:leave="leaderboard-transition-leave"
                     x-transition:leave-start="leaderboard-transition-leave-start"
                     x-transition:leave-end="leaderboard-transition-leave-end">
                    <div class="leaderboard-pcl__tooltip-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <span>Data Leaderboard dan Target Harian akan di-update secara berkala sekali sehari setiap pukul 7.00 WITA</span>
                </div>
            </div>
        @endif
    </div>

    @php
        $topThree = $leaderboardData->take(3);
        $rank4 = $leaderboardData->skip(3)->first();
        $rank5 = $leaderboardData->skip(4)->first();
        $remainingData = $leaderboardData->skip(5);
    @endphp

    {{-- Podium Section --}}
    <section class="leaderboard-pcl__podium">
        {{-- Rank 2 (Left) --}}
        @if($topThree->count() >= 2)
            @php $rank2 = $topThree[1]; @endphp
            <div class="leaderboard-pcl__podium-card leaderboard-pcl__podium-card--rank2">
                <div class="leaderboard-pcl__rank-badge leaderboard-pcl__rank-badge--silver">
                    <span>2</span>
                </div>
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--md">
                    {{ strtoupper(substr($rank2['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pcl__info">
                    <h3 class="leaderboard-pcl__name">{{ $rank2['name'] }}</h3>
                    <p class="leaderboard-pcl__kecamatan">{{ $rank2['kecamatan_string'] }}</p>
                </div>
                <div class="leaderboard-pcl__stats">
                    <div class="leaderboard-pcl__stat">
                        <span class="leaderboard-pcl__stat-value">{{ $rank2['daily_submit'] }}</span>
                        <span class="leaderboard-pcl__stat-label">Hari Ini</span>
                    </div>
                </div>
                <div class="leaderboard-pcl__target-badge {{ $rank2['target_met'] ? 'leaderboard-pcl__target-badge--met' : 'leaderboard-pcl__target-badge--not-met' }}">
                    {{ $rank2['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif

        {{-- Rank 1 (Center - Largest) --}}
        @if($topThree->isNotEmpty())
            @php $rank1 = $topThree[0]; @endphp
            <div class="leaderboard-pcl__podium-card leaderboard-pcl__podium-card--rank1">
                <div class="leaderboard-pcl__crown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5z"/>
                    </svg>
                </div>
                <div class="leaderboard-pcl__rank-badge leaderboard-pcl__rank-badge--gold">
                    <span>1</span>
                </div>
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--lg">
                    {{ strtoupper(substr($rank1['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pcl__info">
                    <h3 class="leaderboard-pcl__name leaderboard-pcl__name--lg">{{ $rank1['name'] }}</h3>
                    <p class="leaderboard-pcl__kecamatan leaderboard-pcl__kecamatan--lg">{{ $rank1['kecamatan_string'] }}</p>
                </div>
                <div class="leaderboard-pcl__stats leaderboard-pcl__stats--lg">
                    <div class="leaderboard-pcl__stat">
                        <span class="leaderboard-pcl__stat-value leaderboard-pcl__stat-value--lg">{{ $rank1['daily_submit'] }}</span>
                        <span class="leaderboard-pcl__stat-label">Hari Ini</span>
                    </div>
                </div>
                <div class="leaderboard-pcl__target-badge {{ $rank1['target_met'] ? 'leaderboard-pcl__target-badge--met' : 'leaderboard-pcl__target-badge--not-met' }}">
                    {{ $rank1['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif

        {{-- Rank 3 (Right) --}}
        @if($topThree->count() >= 3)
            @php $rank3 = $topThree[2]; @endphp
            <div class="leaderboard-pcl__podium-card leaderboard-pcl__podium-card--rank3">
                <div class="leaderboard-pcl__rank-badge leaderboard-pcl__rank-badge--bronze">
                    <span>3</span>
                </div>
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--md">
                    {{ strtoupper(substr($rank3['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pcl__info">
                    <h3 class="leaderboard-pcl__name">{{ $rank3['name'] }}</h3>
                    <p class="leaderboard-pcl__kecamatan">{{ $rank3['kecamatan_string'] }}</p>
                </div>
                <div class="leaderboard-pcl__stats">
                    <div class="leaderboard-pcl__stat">
                        <span class="leaderboard-pcl__stat-value">{{ $rank3['daily_submit'] }}</span>
                        <span class="leaderboard-pcl__stat-label">Hari Ini</span>
                    </div>
                </div>
                <div class="leaderboard-pcl__target-badge {{ $rank3['target_met'] ? 'leaderboard-pcl__target-badge--met' : 'leaderboard-pcl__target-badge--not-met' }}">
                    {{ $rank3['target_met'] ? 'Target Terpenuhi' : 'Belum Target' }}
                </div>
            </div>
        @endif
    </section>

    {{-- Rank 4-5 Cards --}}
    <section class="leaderboard-pcl__runners-up">
        @if($rank4)
            <div class="leaderboard-pcl__runner-card">
                <div class="leaderboard-pcl__rank-badge leaderboard-pcl__rank-badge--4">
                    <span>4</span>
                </div>
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--sm">
                    {{ strtoupper(substr($rank4['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pcl__runner-info">
                    <h4 class="leaderboard-pcl__runner-name">{{ $rank4['name'] }}</h4>
                    <span class="leaderboard-pcl__runner-submit">{{ $rank4['daily_submit'] }} submit</span>
                </div>
            </div>
        @endif

        @if($rank5)
            <div class="leaderboard-pcl__runner-card">
                <div class="leaderboard-pcl__rank-badge leaderboard-pcl__rank-badge--5">
                    <span>5</span>
                </div>
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--sm">
                    {{ strtoupper(substr($rank5['name'], 0, 1)) }}
                </div>
                <div class="leaderboard-pcl__runner-info">
                    <h4 class="leaderboard-pcl__runner-name">{{ $rank5['name'] }}</h4>
                    <span class="leaderboard-pcl__runner-submit">{{ $rank5['daily_submit'] }} submit</span>
                </div>
            </div>
        @endif
    </section>

    {{-- Full Leaderboard Table --}}
    <section class="leaderboard-pcl__table-section">
        <div class="leaderboard-pcl__table-header">
            <h3 class="leaderboard-pcl__table-title">Klasemen Lengkap</h3>
            <div class="leaderboard-pcl__table-meta">
                <span class="leaderboard-pcl__table-count">{{ $leaderboardDataPaginated->total() }} PCL</span>
                @if($leaderboardLastUpdate)
                    <span class="leaderboard-pcl__table-last-update-wrapper" x-data="{ showTooltip: false }">
                        <span class="leaderboard-pcl__table-last-update" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                            Kondisi {{ $leaderboardLastUpdate->setTimezone('Asia/Makassar')->format('j F Y H:i') }} WITA
                        </span>
                        <span class="leaderboard-pcl__table-last-update-tooltip"
                              x-show="showTooltip"
                              x-transition:enter="leaderboard-transition-enter"
                              x-transition:enter-start="leaderboard-transition-enter-start"
                              x-transition:enter-end="leaderboard-transition-enter-end"
                              x-transition:leave="leaderboard-transition-leave"
                              x-transition:leave-start="leaderboard-transition-leave-start"
                              x-transition:leave-end="leaderboard-transition-leave-end">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>Data Leaderboard dan Target Harian akan di-update secara berkala sekali sehari setiap pukul 7.00 WITA</span>
                        </span>
                    </span>
                @endif
            </div>
        </div>

        {{-- Filter by Status Target --}}
        <div class="leaderboard-pcl__filters">
            <div class="leaderboard-pcl__filter-group">
                <label class="leaderboard-pcl__filter-label">Filter Status:</label>
                <select id="leaderboard-status-filter" class="leaderboard-pcl__filter-select" x-model="statusFilter" @change="loadPage(1)">
                    <option value="">Semua</option>
                    <option value="met">Target Terpenuhi</option>
                    <option value="not_met">Belum Target</option>
                </select>
            </div>
        </div>

        <div class="leaderboard-pcl__table-wrapper">
            <table class="leaderboard-pcl__table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Nama PCL</th>
                        <th>Wilayah Kerja</th>
                        <th>Submit Harian</th>
                        <th>Status Target</th>
                    </tr>
                </thead>
                <tbody id="leaderboard-table-body">
                    @foreach($leaderboardDataPaginated as $index => $pcl)
                        @php
                            $globalRank = ($leaderboardDataPaginated->currentPage() - 1) * $leaderboardDataPaginated->perPage() + $index + 1;
                        @endphp
                        <tr class="{{ $pcl['target_met'] ? 'leaderboard-pcl__row--met' : 'leaderboard-pcl__row--not-met' }}">
                            <td class="leaderboard-pcl__rank-cell">
                                <span class="leaderboard-pcl__rank-number">{{ $globalRank }}</span>
                            </td>
                            <td class="leaderboard-pcl__name-cell">
                                <div class="leaderboard-pcl__name-cell-content">
                                    <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--xs">
                                        {{ strtoupper(substr($pcl['name'], 0, 1)) }}
                                    </div>
                                    <span>{{ $pcl['name'] }}</span>
                                </div>
                            </td>
                            <td class="leaderboard-pcl__kecamatan-cell">{{ $pcl['kecamatan_string'] }}</td>
                            <td class="leaderboard-pcl__submit-cell">
                                <span class="leaderboard-pcl__submit-value">{{ $pcl['daily_submit'] }}</span>
                            </td>
                            <td class="leaderboard-pcl__status-cell">
                                <span class="leaderboard-pcl__status-badge {{ $pcl['target_met'] ? 'leaderboard-pcl__status-badge--met' : 'leaderboard-pcl__status-badge--not-met' }}">
                                    {{ $pcl['target_met'] ? 'Terpenuhi' : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="leaderboard-pagination-wrapper">
            @include('components.partials.leaderboard-pagination', ['leaderboardPaginated' => $leaderboardDataPaginated])
        </div>
    </section>
</div>

<script>
    function leaderboardPcl() {
        return {
            currentPage: {{ $leaderboardDataPaginated->currentPage() }},
            totalCount: {{ $leaderboardDataPaginated->total() }},
            statusFilter: '',

            initPagination() {
                const paginationWrapper = document.getElementById('leaderboard-pagination-wrapper');
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
                const tableBody = document.getElementById('leaderboard-table-body');
                const paginationWrapper = document.getElementById('leaderboard-pagination-wrapper');
                const statusFilter = this.statusFilter;

                let url = `/leaderboard-pcl-page?page=${page}`;
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
        const paginationWrapper = document.getElementById('leaderboard-pagination-wrapper');
        if (paginationWrapper) {
            paginationWrapper.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-page]');
                if (!btn) return;

                e.preventDefault();
                const page = btn.dataset.page;
                const tableBody = document.getElementById('leaderboard-table-body');
                const paginationWrapperEl = document.getElementById('leaderboard-pagination-wrapper');
                const statusFilter = document.getElementById('leaderboard-status-filter')?.value || '';

                let url = `/leaderboard-pcl-page?page=${page}`;
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
