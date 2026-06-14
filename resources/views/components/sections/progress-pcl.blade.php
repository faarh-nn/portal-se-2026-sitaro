<div class="pcl-section" x-data="pclTable()">
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

            <div class="pcl-stat-card pcl-stat-card--completed">
                <div class="pcl-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="pcl-stat-card__content">
                    <span class="pcl-stat-card__value">{{ number_format($pclTotals['completed']) }}</span>
                    <span class="pcl-stat-card__label">Completed</span>
                </div>
            </div>
        </div>

        <div class="pcl-table-section">
            <div class="pcl-table-header">
                <div class="pcl-table-controls">
                    <div class="pcl-table-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input
                            type="text"
                            id="pcl-search"
                            x-model="search"
                            x-on:input.debounce.300ms="searchData()"
                            placeholder="Cari berdasarkan nama PCL..."
                            class="pcl-table-search__input"
                        />
                        <button
                            x-show="search"
                            x-on:click="clearSearch()"
                            class="pcl-table-search__clear"
                            aria-label="Clear search"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="pcl-table-filter">
                        <label for="pcl-filter-pml" class="pcl-table-filter__label">Filter berdasarkan PML:</label>
                        <select
                            id="pcl-filter-pml"
                            x-model="pmlFilter"
                            x-on:change="filterByPml()"
                            class="pcl-table-filter__select"
                        >
                            <option value="">Semua PML</option>
                            @foreach($pmlList as $pmlName)
                                <option value="{{ $pmlName }}">{{ $pmlName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pcl-table-meta">
                    <span class="pcl-table-count" x-text="totalCount + ' data'"></span>
                    @if($lastUpdate)
                        <span class="pcl-table-last-update">
                            Kondisi {{ $lastUpdate->setTimezone('Asia/Makassar')->format('j F Y') }} 05.00 WITA
                        </span>
                    @endif
                </div>
            </div>

            <div class="pcl-table-wrapper">
                <table class="pcl-table" id="pcl-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama PCL</th>
                            <th>Open</th>
                            <th>Submit</th>
                            <th>Reject</th>
                            <th>Completed</th>
                            <th>Nama PML</th>
                            <th class="pcl-table__sortable" x-on:click="toggleSort('submit_ratio')">
                                Submit/Assignment
                                <span class="pcl-table__sort-icon">
                                    <svg x-show="sortBy !== 'submit_ratio'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 15l5 5 5-5M7 9l5-5 5 5"/>
                                    </svg>
                                    <svg x-show="sortBy === 'submit_ratio' && sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 9l5-5 5 5"/>
                                    </svg>
                                    <svg x-show="sortBy === 'submit_ratio' && sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 15l5 5 5-5"/>
                                    </svg>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pcl-table-body">
                        @foreach($pclDataPaginated as $index => $pcl)
                            @php
                                $pclBarColor = $pcl['submit_ratio'] == 100 ? '#10B981' : ($pcl['submit_ratio'] >= 71 ? '#00a6fb' : ($pcl['submit_ratio'] >= 41 ? '#F59E0B' : '#EF4444'));
                                $rowNumber = ($pclDataPaginated->currentPage() - 1) * $pclDataPaginated->perPage() + $index + 1;
                            @endphp
                            <tr>
                                <td class="pcl-table__no">{{ $rowNumber }}</td>
                                <td class="pcl-table__name">{{ $pcl['name'] }}</td>
                                <td class="pcl-table__open">{{ $pcl['open'] }}</td>
                                <td class="pcl-table__submit">{{ $pcl['submit'] }}</td>
                                <td class="pcl-table__reject">{{ $pcl['reject'] }}</td>
                                <td class="pcl-table__completed">{{ $pcl['completed'] }}</td>
                                <td class="pcl-table__pml">{{ $pcl['pml'] }}</td>
                                <td class="pcl-table__submit-ratio">
                                    <div class="pcl-progress">
                                        <div class="pcl-progress__bar">
                                            <div class="pcl-progress__fill" style="width: {{ $pcl['submit_ratio'] }}%; background-color: {{ $pclBarColor }}"></div>
                                        </div>
                                        <span class="pcl-progress__value">{{ $pcl['submit'] }}/{{ $pcl['target'] }} ({{ $pcl['submit_ratio'] }}%)</span>
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
    function pclTable() {
        return {
            search: '',
            pmlFilter: '',
            sortBy: '',
            sortDirection: 'asc',
            currentPage: {{ $pclDataPaginated->currentPage() }},
            totalCount: {{ $pclDataPaginated->total() }},

            toggleSort(column) {
                if (this.sortBy === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = column;
                    this.sortDirection = 'asc';
                }
                this.currentPage = 1;
                this.loadData();
            },

            searchData() {
                this.currentPage = 1;
                this.loadData();
            },

            filterByPml() {
                this.currentPage = 1;
                this.loadData();
            },

            clearSearch() {
                this.search = '';
                this.currentPage = 1;
                this.loadData();
            },

            clearPmlFilter() {
                this.pmlFilter = '';
                this.currentPage = 1;
                this.loadData();
            },

            buildUrl() {
                const params = new URLSearchParams();
                params.append('page', this.currentPage);
                if (this.search) {
                    params.append('search', this.search);
                }
                if (this.pmlFilter) {
                    params.append('pml', this.pmlFilter);
                }
                if (this.sortBy) {
                    params.append('sortBy', this.sortBy);
                    params.append('sortDirection', this.sortDirection);
                }
                return `/pcl-table-page?${params.toString()}`;
            },

            loadData(page = null) {
                if (page) {
                    this.currentPage = page;
                }

                const tableBody = document.getElementById('pcl-table-body');
                const paginationWrapper = document.getElementById('pcl-pagination-wrapper');
                const url = this.buildUrl();

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = data.html;
                        paginationWrapper.innerHTML = data.pagination;
                        this.totalCount = data.total;
                        this.initPagination();
                    })
                    .catch(error => console.error('Error loading page:', error));
            },

            initPagination() {
                const paginationWrapper = document.getElementById('pcl-pagination-wrapper');
                paginationWrapper.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-page]');
                    if (!btn) return;

                    e.preventDefault();
                    const page = btn.dataset.page;
                    this.loadData(page);
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const paginationWrapper = document.getElementById('pcl-pagination-wrapper');
        paginationWrapper.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-page]');
            if (!btn) return;

            e.preventDefault();
            const page = btn.dataset.page;
            const alpineData = document.querySelector('[x-data]').__x.$data;
            const search = alpineData.search;
            const pmlFilter = alpineData.pmlFilter;
            const sortBy = alpineData.sortBy;
            const sortDirection = alpineData.sortDirection;

            const params = new URLSearchParams();
            params.append('page', page);
            if (search) {
                params.append('search', search);
            }
            if (pmlFilter) {
                params.append('pml', pmlFilter);
            }
            if (sortBy) {
                params.append('sortBy', sortBy);
                params.append('sortDirection', sortDirection);
            }

            const tableBody = document.getElementById('pcl-table-body');
            const paginationWrapperEl = document.getElementById('pcl-pagination-wrapper');

            fetch(`/pcl-table-page?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = data.html;
                    paginationWrapperEl.innerHTML = data.pagination;
                    document.querySelector('[x-data]').__x.$data.currentPage = page;
                    document.querySelector('[x-data]').__x.$data.totalCount = data.total;
                })
                .catch(error => console.error('Error loading page:', error));
        });
    });
</script>