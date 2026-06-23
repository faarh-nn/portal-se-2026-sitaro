<div class="pml-section" x-data="pmlTable()" id="progress-pml">
    <div class="pml-section__header">
        <span class="pml-section__eyebrow">Monitoring PML</span>
        <h2 class="pml-section__title">Progress per PML (Petugas Pemeriksa Lapangan)</h2>
        <p class="pml-section__description">
            Monitoring progress pekerjaan lapangan setiap PML berdasarkan status objek kuesioner.
        </p>
    </div>

    <section class="pml-section__container">
        <div class="pml-stats-row">
            <div class="pml-stat-card pml-stat-card--submit">
                <div class="pml-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </div>
                <div class="pml-stat-card__content">
                    <span class="pml-stat-card__value">{{ number_format($pmlTotals['submit']) }}</span>
                    <span class="pml-stat-card__label">Submit</span>
                </div>
            </div>

            <div class="pml-stat-card pml-stat-card--reject">
                <div class="pml-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div class="pml-stat-card__content">
                    <span class="pml-stat-card__value">{{ number_format($pmlTotals['reject']) }}</span>
                    <span class="pml-stat-card__label">Reject</span>
                </div>
            </div>

            <div class="pml-stat-card pml-stat-card--approved">
                <div class="pml-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="pml-stat-card__content">
                    <span class="pml-stat-card__value">{{ number_format($pmlTotals['approved']) }}</span>
                    <span class="pml-stat-card__label">Approved</span>
                </div>
            </div>
        </div>

        <div class="pml-table-section">
            <div class="pml-table-header">
                <div class="pml-table-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        id="pml-search"
                        x-model="search"
                        placeholder="Cari berdasarkan nama PML..."
                        class="pml-table-search__input"
                    />
                    <button
                        x-show="search"
                        x-on:click="search = ''"
                        class="pml-table-search__clear"
                        aria-label="Clear search"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="pml-table-meta">
                    <span class="pml-table-count" x-text="filteredData.length + ' data'"></span>
                    @if($pmlLastUpdate)
                        <span class="pml-table-last-update-wrapper" x-data="{ showTooltip: false }">
                            <span class="pml-table-last-update" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                                Kondisi {{ $pmlLastUpdate->imported_at->setTimezone('Asia/Makassar')->format('j F Y H:i') }} WITA
                            </span>
                            <span class="pml-table-last-update-tooltip"
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
                                <span>Data progress PML akan di-update secara berkala dua kali sehari setiap pukul 07.00 WITA dan 20.00 WITA</span>
                            </span>
                        </span>
                    @endif
                    <a href="{{ route('pml.export') }}" class="pml-table-download" title="Unduh Excel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        <span>Unduh</span>
                    </a>
                </div>
            </div>

            <div class="pml-table-wrapper">
                <table class="pml-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama PML</th>
                            <th>Submit</th>
                            <th>Reject</th>
                            <th>Approved</th>
                            <th class="pml-table__sortable" x-on:click="toggleSort('progress')">
                                Approved/Total Assignment
                                <span class="pml-table__sort-icon">
                                    <svg x-show="sortBy !== 'progress'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 15l5 5 5-5M7 9l5-5 5 5"/>
                                    </svg>
                                    <svg x-show="sortBy === 'progress' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 9l5-5 5 5"/>
                                    </svg>
                                    <svg x-show="sortBy === 'progress' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 15l5 5 5-5"/>
                                    </svg>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(pml, index) in filteredData" :key="pml.name">
                            <tr>
                                <td class="pml-table__no" x-text="index + 1"></td>
                                <td class="pml-table__name" x-text="pml.name"></td>
                                <td class="pml-table__submit" x-text="pml.submit"></td>
                                <td class="pml-table__reject" x-text="pml.reject"></td>
                                <td class="pml-table__approved-value" x-text="pml.approved"></td>
                                <td class="pml-table__approved">
                                    <div class="pml-progress">
                                        <div class="pml-progress__bar">
                                            <div class="pml-progress__fill" :style="`width: ${pml.progress}%; background-color: ${pml.progress === 100 ? '#10B981' : (pml.progress >= 71 ? '#00a6fb' : (pml.progress >= 41 ? '#F59E0B' : '#EF4444'))}`"></div>
                                        </div>
                                        <span class="pml-progress__value" x-text="`${pml.approved}/${pml.target} (${pml.progress}%)`"></span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredData.length === 0">
                            <td colspan="6" class="pml-table__empty">Tidak ada data yang cocok</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
    function pmlTable() {
        return {
            search: '',
            sortBy: '',
            sortDirection: 'asc',
            pmlData: @json($pmlData),
            toggleSort(column) {
                if (this.sortBy === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = column;
                    this.sortDirection = 'asc';
                }
            },
            get sortedData() {
                let data = [...this.pmlData];

                // Apply search filter
                if (this.search) {
                    const searchLower = this.search.toLowerCase();
                    data = data.filter(pml => pml.name.toLowerCase().includes(searchLower));
                }

                // Apply sorting
                if (this.sortBy) {
                    data.sort((a, b) => {
                        let aVal = a[this.sortBy];
                        let bVal = b[this.sortBy];
                        if (this.sortDirection === 'asc') {
                            return aVal - bVal;
                        } else {
                            return bVal - aVal;
                        }
                    });
                }

                return data;
            },
            get filteredData() {
                return this.sortedData;
            }
        }
    }
</script>