<section class="monitoring-section" id="peta-kecamatan">
    <div class="monitoring-section__header">
        <span class="monitoring-section__eyebrow">Monitoring Kecamatan</span>
        <h2 class="monitoring-section__title">Progress Pencacahan per Kecamatan</h2>
        <p class="monitoring-section__description">
            Peta interaktif menampilkan progress pencacahan Sensus Ekonomi 2026 di wilayah Kabupaten Kepulauan Siau Tagulandang Biaro berdasarkan kecamatan.
        </p>
    </div>

    <div class="monitoring-section__container">
        <div class="monitoring-stats-row">
            <div class="monitoring-stat-card monitoring-stat-card--overall">
                <div class="monitoring-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="monitoring-stat-card__content">
                    <span class="monitoring-stat-card__value">{{ $overallProgress }}%</span>
                    <span class="monitoring-stat-card__label">Progress Keseluruhan</span>
                </div>
            </div>

            <div class="monitoring-stat-card monitoring-stat-card--target">
                <div class="monitoring-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <circle cx="12" cy="12" r="6"></circle>
                        <circle cx="12" cy="12" r="2"></circle>
                    </svg>
                </div>
                <div class="monitoring-stat-card__content">
                    <span class="monitoring-stat-card__value">{{ number_format($totalTarget) }}</span>
                    <span class="monitoring-stat-card__label">Target Pencacahan</span>
                </div>
            </div>

            <div class="monitoring-stat-card monitoring-stat-card--completed">
                <div class="monitoring-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                </div>
                <div class="monitoring-stat-card__content">
                    <span class="monitoring-stat-card__value">{{ number_format($totalCompleted) }}</span>
                    <span class="monitoring-stat-card__label">Tercacah (Submit)</span>
                </div>
            </div>
        </div>

        <div class="monitoring-section__legend">
            <div class="monitoring-legend">
                <span class="monitoring-legend__title">Tingkat Progress di Peta:</span>
                <div class="monitoring-legend__items">
                    <div class="monitoring-legend__gradient-wrapper">
                        <span class="monitoring-legend__gradient-label">Progress rendah</span>
                        <span class="monitoring-legend__gradient"></span>
                        <span class="monitoring-legend__gradient-label">Progress tinggi</span>
                    </div>
                    <div class="monitoring-legend__below-average">
                        <span class="monitoring-legend__below-average-badge"></span>
                        <span class="monitoring-legend__text">Di bawah rata-rata</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="monitoring-section__wrapper">
            <div id="monitoring-map" class="monitoring-map"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressData = @json($progressData);

    // Get green gradient color based on progress percentage
    // Darker green = higher progress, lighter green = lower progress
    function getProgressColor(progress) {
        // Interpolate between light green (0%) and dark green (100%)
        const minColor = { r: 209, g: 247, b: 217 }; // Light green #D1F7D9
        const maxColor = { r: 16, g: 185, b: 129 };  // Dark green #10B981

        const ratio = progress / 100;
        const r = Math.round(minColor.r + (maxColor.r - minColor.r) * ratio);
        const g = Math.round(minColor.g + (maxColor.g - minColor.g) * ratio);
        const b = Math.round(minColor.b + (maxColor.b - minColor.b) * ratio);

        return `rgb(${r}, ${g}, ${b})`;
    }

    // Initialize map centered on Sitaro
    const map = L.map('monitoring-map').setView([2.7307908972918296, 125.40917238648026], 12);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 20,
        subdomains: 'abcd'
    }).addTo(map);

    // Add blue overlay for dark blue background effect
    const blueSvg = 'data:image/svg+xml;base64,' + btoa('<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256"><rect fill="#023e8a" opacity="0.8" width="256" height="256"/></svg>');
    L.tileLayer(blueSvg, {
        opacity: 0.8,
        interactive: false
    }).addTo(map);

    // Load GeoJSON for kecamatan boundaries
    fetch('/assets/data/sitaro_kecamatan.geojson')
        .then(response => response.json())
        .then(geojsonData => {
            // Create a mapping from NAMOBJ to progress data
            // The geojson uses "Biaro" but progress data might have different names
            const progressMap = {
                'Biaro': 'Biaro',
                'Tagulandang Selatan': 'Tagulandang Selatan',
                'Tagulandang Utara': 'Tagulandang Utara',
                'Tagulandang': 'Tagulandang',
                'Siau Barat Selatan': 'Siau Barat Selatan',
                'Siau Timur Selatan': 'Siau Timur Selatan',
                'Siau Barat': 'Siau Barat',
                'Siau Tengah': 'Siau Tengah',
                'Siau Timur': 'Siau Timur',
                'Siau Barat Utara': 'Siau Barat Utara',
            };

            // Calculate average progress
            const progressValues = Object.values(progressData).map(d => d.progress);
            const averageProgress = progressValues.reduce((a, b) => a + b, 0) / progressValues.length;

            L.geoJSON(geojsonData, {
                style: function(feature) {
                    const kecamatanName = feature.properties.NAMOBJ;
                    const progressKey = progressMap[kecamatanName];
                    const data = progressKey ? progressData[progressKey] : null;
                    const progress = data ? data.progress : 0;

                    // Below average = red, Above/Average = green gradient
                    const isBelowAverage = progress < averageProgress;
                    const fillColor = isBelowAverage ? '#ef4444' : getProgressColor(progress);
                    const borderColor = isBelowAverage ? '#dc2626' : '#10B981';

                    return {
                        fillColor: fillColor,
                        fillOpacity: 0.5,
                        color: borderColor,
                        weight: 2,
                        opacity: 1
                    };
                },
                onEachFeature: function(feature, layer) {
                    const kecamatanName = feature.properties.NAMOBJ;
                    const progressKey = progressMap[kecamatanName];
                    const data = progressKey ? progressData[progressKey] : null;

                    if (data) {
                        const progress = data.progress;
                        const isBelowAverage = progress < averageProgress;
                        const status = progress == 100 ? 'Selesai' :
                                       progress >= 71 ? 'Tinggi' :
                                       progress >= 41 ? 'Sedang' : 'Rendah';

                        const popupContent = `
                            <div style="min-width: 200px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; color: #231f20;">Kec. ${kecamatanName}</h3>
                                <div style="margin-bottom: 8px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                        <span style="font-size: 13px; color: #696969;">Progress:</span>
                                        <span style="font-size: 14px; font-weight: 700; color: ${isBelowAverage ? '#ef4444' : '#10B981'};">${progress}%</span>
                                    </div>
                                    <div style="background: #e5e5e5; height: 8px; border-radius: 4px; overflow: hidden;">
                                        <div style="background: ${isBelowAverage ? '#ef4444' : getProgressColor(progress)}; height: 100%; width: ${progress}%; border-radius: 4px;"></div>
                                    </div>
                                </div>
                                <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Tercacah:</strong> ${data.completed.toLocaleString('id-ID')}</p>
                                <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Target:</strong> ${data.target.toLocaleString('id-ID')}</p>
                                <p style="margin: 4px 0; font-size: 13px; color: #696969;"><strong>Rata-rata:</strong> ${averageProgress.toFixed(1)}%</p>
                                <p style="margin: 8px 0 0 0; font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 4px; display: inline-block; background-color: ${isBelowAverage ? '#ef4444' : '#10B981'}; color: white;">${status}</p>
                            </div>
                        `;

                        layer.bindPopup(popupContent);
                        layer.bindTooltip(
                            `<strong>Kec. ${kecamatanName}</strong><br/>` +
                            `<span style="color:${isBelowAverage ? '#ef4444' : '#10B981'}">Progress: ${progress}%</span>`,
                            {
                                direction: 'top',
                                offset: [0, -10],
                                className: 'custom-tooltip',
                                sticky: true,
                                interactive: false
                            }
                        );

                        layer.on('mouseover', function(e) {
                            layer.openTooltip();
                        });

                        layer.on('mouseout', function(e) {
                            layer.closeTooltip();
                        });
                    } else {
                        layer.bindPopup(`<h3 style="margin: 0;">Kec. ${kecamatanName}</h3><p style="margin: 8px 0 0 0; font-size: 13px; color: #696969;">Data progress tidak tersedia</p>`);
                    }
                }
            }).addTo(map);
        })
        .catch(error => {
            console.error('Error loading GeoJSON:', error);
            document.getElementById('monitoring-map').innerHTML = '<p style="text-align:center;padding:40px;color:#696969;">Gagal memuat data peta. Silakan refresh halaman.</p>';
        });
});
</script>