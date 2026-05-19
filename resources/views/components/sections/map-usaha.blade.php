<section class="map-section" id="peta-usaha">
    <div class="map-section__header">
        <span class="map-section__eyebrow">Peta Interaktif</span>
        <h2 class="map-section__title">Persebaran Lokasi Usaha Hasil Scraping Google Maps</h2>
        <p class="map-section__description">
            Peta interaktif menampilkan lokasi usaha ekonomi hasil scraping Google Maps di wilayah Kabupaten Kepulauan Siau Tagulandang Biaro berdasarkan koordinat geografis.
        </p>
    </div>

    <div class="map-section__container">
        <div class="map-stats-row">
            <div class="map-stat-card map-stat-card--total">
                <div class="map-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <div class="map-stat-card__content">
                    <span class="map-stat-card__value">{{ $statsGmaps['total'] }}</span>
                    <span class="map-stat-card__label">Total Usaha</span>
                </div>
            </div>

            <div class="map-stat-card map-stat-card--sbr">
                <div class="map-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="map-stat-card__content">
                    <span class="map-stat-card__value">{{ $statsGmaps['dalam_sbr'] }}</span>
                    <span class="map-stat-card__label">Dalam SBR</span>
                </div>
            </div>

            <div class="map-stat-card map-stat-card--outside">
                <div class="map-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="map-stat-card__content">
                    <span class="map-stat-card__value">{{ $statsGmaps['luar_sbr'] }}</span>
                    <span class="map-stat-card__label">Luar SBR</span>
                </div>
            </div>
        </div>

        <div class="map-section__legend">
            <div class="map-legend">
                <span class="map-legend__title">Keterangan:</span>
                <div class="map-legend__items">
                    <div class="map-legend__item">
                        <span class="map-legend__marker map-legend__marker--sbr"></span>
                        <span>Dalam SBR</span>
                    </div>
                    <div class="map-legend__item">
                        <span class="map-legend__marker map-legend__marker--outside"></span>
                        <span>Luar SBR</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="map-section__wrapper">
            <div id="usaha-map" class="usaha-map"></div>
        </div>

        <div class="map-table-section">
            <div class="map-table-section__header">
                <h3 class="map-table-section__title">Daftar Usaha Hasil Scraping Google Maps</h3>
                <div class="map-table-section__controls">
                    <div class="map-table-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="table-search" placeholder="Cari usaha..." value="{{ request('search') }}" />
                    </div>
                    <div class="map-table-filter">
                        <select id="kategori-filter">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriOptions as $kategori)
                                <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="table-wrapper">
                <table class="map-table" id="usaha-table">
                    <thead>
                        <tr>
                            <th>Nama Usaha</th>
                            <th>Kategori</th>
                            <th>Alamat</th>
                            <th>Nomor Telepon</th>
                            <th>Website</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsahaGmaps as $usaha)
                            <tr>
                                <td class="map-table__name">{{ $usaha->nama_usaha }}</td>
                                <td><span class="map-table__badge">{{ $usaha->kategori ?? '-' }}</span></td>
                                <td class="map-table__address">{{ $usaha->alamat ?? '-' }}</td>
                                <td>{{ $usaha->nomor_telepon ?? '-' }}</td>
                                <td>
                                    @if($usaha->website)
                                        <a href="{{ $usaha->website }}" target="_blank" class="map-table__link">Buka</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="pagination-wrapper">
                @include('components.partials.table-pagination', ['allUsahaGmaps' => $allUsahaGmaps])
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const usahaData = @json($usahaGmaps);

    if (usahaData.length === 0) {
        document.getElementById('usaha-map').innerHTML = '<p style="text-align:center;padding:40px;color:#696969;">Tidak ada data lokasi usaha</p>';
    } else {
        const map = L.map('usaha-map').setView([2.7307908972918296, 125.40917238648026], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const greenIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="marker-pin marker-pin--sbr"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const blueIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="marker-pin marker-pin--outside"></div>',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        usahaData.forEach(function(usaha) {
            if (usaha.latitude && usaha.longitude) {
                const icon = usaha.is_in_sbr ? greenIcon : blueIcon;
                const status = usaha.is_in_sbr ? 'Dalam SBR' : 'Luar SBR';

                let popupContent = `
                    <div style="min-width: 200px;">
                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #231f20;">${usaha.nama_usaha}</h3>
                        <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Kategori:</strong> ${usaha.kategori || '-'}</p>
                        <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Alamat:</strong> ${usaha.alamat || '-'}</p>
                        <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Telepon:</strong> ${usaha.nomor_telepon || '-'}</p>
                        <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Website:</strong> ${usaha.website || '-'}</p>
                        <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Jam Operasional:</strong> ${usaha.jam_operasional || '-'}</p>
                        <p style="margin: 8px 0 0 0; font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; display: inline-block; ${usaha.is_in_sbr ? 'background-color: #10B981; color: white;' : 'background-color: #00a6fb; color: white;'}">${status}</p>
                    </div>
                `;

                L.marker([usaha.latitude, usaha.longitude], { icon: icon })
                    .bindPopup(popupContent)
                    .bindTooltip(
                        `<strong>${usaha.nama_usaha}</strong><br/>` +
                        `<span style="color:#10B981;">${usaha.kategori || '-'}</span><br/>` +
                        `<span style="color:#696969;font-size:12px;">${usaha.alamat || '-'}</span>`,
                        {
                            direction: 'top',
                            offset: [0, -10],
                            className: 'custom-tooltip'
                        }
                    )
                    .addTo(map);
            }
        });
    }

    // Table search functionality
    const tableSearch = document.getElementById('table-search');
    const kategoriFilter = document.getElementById('kategori-filter');
    const table = document.getElementById('usaha-table');
    const tableWrapper = document.getElementById('table-wrapper');
    const paginationWrapper = document.getElementById('pagination-wrapper');

    function filterTable() {
        const searchTerm = tableSearch ? tableSearch.value.toLowerCase() : '';
        const selectedKategori = kategoriFilter ? kategoriFilter.value.toLowerCase() : '';
        const rows = table ? table.querySelectorAll('tbody tr') : [];

        rows.forEach(row => {
            const name = row.querySelector('.map-table__name').textContent.toLowerCase();
            const kategori = row.querySelector('.map-table__badge').textContent.toLowerCase();
            const address = row.querySelector('.map-table__address').textContent.toLowerCase();

            const matchesSearch = name.includes(searchTerm) || address.includes(searchTerm);
            const matchesKategori = selectedKategori === '' || kategori.includes(selectedKategori);

            row.style.display = (matchesSearch && matchesKategori) ? '' : 'none';
        });
    }

    function loadTablePage(page) {
        const searchTerm = tableSearch ? tableSearch.value : '';
        const selectedKategori = kategoriFilter ? kategoriFilter.value : '';

        const url = `/table-page?page=${page}${searchTerm ? '&search=' + encodeURIComponent(searchTerm) : ''}${selectedKategori ? '&kategori=' + encodeURIComponent(selectedKategori) : ''}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tableWrapper.innerHTML = data.html;
                paginationWrapper.innerHTML = data.pagination;

                if (tableSearch && kategoriFilter) {
                    filterTable();
                }
            })
            .catch(error => console.error('Error loading page:', error));
    }

    // Event listeners
    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            filterTable();
            loadTablePage(1);
        });
    }

    if (kategoriFilter) {
        kategoriFilter.addEventListener('change', function() {
            filterTable();
            loadTablePage(1);
        });
    }

    paginationWrapper.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-page]');
        if (!btn) return;

        e.preventDefault();
        const page = btn.dataset.page;
        loadTablePage(page);
    });
});
</script>