<section class="stats-section" id="statistik-usaha" x-data="statsTables()">
    <div class="stats-section__header">
        <span class="stats-section__eyebrow">Statistik Usaha</span>
        <h2 class="stats-section__title">Distribusi Usaha di Kabupaten Kepulauan Siau Tagulandang Biaro (Prelist SBR)</h2>
        <p class="stats-section__description">
            Data komprehensif tentang persebaran usaha ekonomi berdasarkan skala usaha, kecamatan, dan desa di wilayah Kabupaten Kepulauan Siau Tagulandang Biaro.
        </p>
    </div>

    <div class="stats-section__container">
        <div class="stats-section__summary">
            <div class="stats-total-card">
                <div class="stats-total-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="stats-total-card__content">
                    <span class="stats-total-card__value">{{ $totalUsaha }}</span>
                    <span class="stats-total-card__label">Total Usaha</span>
                </div>
            </div>

            <div class="stats-scale-cards">
                <div class="stats-scale-card stats-scale-card--umk">
                    <div class="stats-scale-card__value-wrapper">
                        <span class="stats-scale-card__value">{{ $statsBySkala['UMK'] }}</span>
                        <span class="stats-scale-card__badge stats-scale-card__badge--umk">UMK</span>
                    </div>
                    <span class="stats-scale-card__label">Usaha Mikro Kecil</span>
                </div>

                <div class="stats-scale-card stats-scale-card--um">
                    <div class="stats-scale-card__value-wrapper">
                        <span class="stats-scale-card__value">{{ $statsBySkala['UM'] }}</span>
                        <span class="stats-scale-card__badge stats-scale-card__badge--um">UM</span>
                    </div>
                    <span class="stats-scale-card__label">Usaha Menengah</span>
                </div>

                <div class="stats-scale-card stats-scale-card--ub">
                    <div class="stats-scale-card__value-wrapper">
                        <span class="stats-scale-card__value">{{ $statsBySkala['UB'] }}</span>
                        <span class="stats-scale-card__badge stats-scale-card__badge--ub">UB</span>
                    </div>
                    <span class="stats-scale-card__label">Usaha Besar</span>
                </div>
            </div>
        </div>

        <div class="stats-section__tables">
            <div class="stats-table-card">
                <h3 class="stats-table-card__title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Berdasarkan Kecamatan
                </h3>
                <div class="stats-table-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        id="kecamatan-search"
                        x-model="kecamatanSearch"
                        placeholder="Cari kecamatan..."
                        class="stats-table-search__input"
                    />
                    <button
                        x-show="kecamatanSearch"
                        x-on:click="kecamatanSearch = ''"
                        class="stats-table-search__clear"
                        aria-label="Clear search"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="stats-table-wrapper">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Kecamatan</th>
                                <th>Total</th>
                                <th>UMK</th>
                                <th>UM</th>
                                <th>UB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsByKecamatan as $kecamatan => $data)
                                <tr x-show="filterKecamatan('{{ $kecamatan }}')">
                                    <td class="stats-table__kecamatan">{{ $kecamatan }}</td>
                                    <td class="stats-table__total">{{ $data['total'] }}</td>
                                    <td class="stats-table__umk">{{ $data['UMK'] }}</td>
                                    <td class="stats-table__um">{{ $data['UM'] }}</td>
                                    <td class="stats-table__ub">{{ $data['UB'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="stats-table-card">
                <h3 class="stats-table-card__title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Berdasarkan Desa/Kelurahan
                </h3>
                <div class="stats-table-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        id="desa-search"
                        x-model="desaSearch"
                        placeholder="Cari desa/kelurahan..."
                        class="stats-table-search__input"
                    />
                    <button
                        x-show="desaSearch"
                        x-on:click="desaSearch = ''"
                        class="stats-table-search__clear"
                        aria-label="Clear search"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="stats-table-wrapper">
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Desa/Kelurahan</th>
                                <th>Kecamatan</th>
                                <th>Total</th>
                                <th>UMK</th>
                                <th>UM</th>
                                <th>UB</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsByDesa as $desa => $data)
                                <tr x-show="filterDesa('{{ $desa }}')">
                                    <td class="stats-table__desa">{{ $desa }}</td>
                                    <td class="stats-table__kecamatan-muted">{{ $data['kecamatan'] }}</td>
                                    <td class="stats-table__total">{{ $data['total'] }}</td>
                                    <td class="stats-table__umk">{{ $data['UMK'] }}</td>
                                    <td class="stats-table__um">{{ $data['UM'] }}</td>
                                    <td class="stats-table__ub">{{ $data['UB'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function statsTables() {
        return {
            kecamatanSearch: '',
            desaSearch: '',

            filterKecamatan(kecamatan) {
                if (!this.kecamatanSearch) return true;
                return kecamatan.toLowerCase().includes(this.kecamatanSearch.toLowerCase());
            },

            filterDesa(desa) {
                if (!this.desaSearch) return true;
                return desa.toLowerCase().includes(this.desaSearch.toLowerCase());
            }
        }
    }
</script>