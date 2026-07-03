<section class="stats-overview">
    @php
        $donutData = [
            ['label' => 'Open', 'value' => $statusDistribution['open'] ?? 0, 'color' => '#94a3b8'],
            ['label' => 'Draft', 'value' => $statusDistribution['draft'] ?? 0, 'color' => '#fbbf24'],
            ['label' => 'Submit', 'value' => $statusDistribution['submit'] ?? 0, 'color' => '#8B5CF6'],
            ['label' => 'Approve', 'value' => $statusDistribution['approve'] ?? 0, 'color' => '#22c55e'],
            ['label' => 'Reject', 'value' => $statusDistribution['reject'] ?? 0, 'color' => '#ef4444'],
        ];
    @endphp
    @php
        $chartJsData = [
            'labels' => array_column($donutData, 'label'),
            'values' => array_column($donutData, 'value'),
            'colors' => array_column($donutData, 'color'),
        ];
    @endphp
    <div class="stats-overview__card" x-data="{
        activeSegment: null,
        labels: {{ json_encode(array_column($donutData, 'label')) }},
        values: {{ json_encode(array_column($donutData, 'value')) }},
        total: {{ $totalStatus }},
        percentages: {{ json_encode(array_map(fn($item) => $totalStatus > 0 ? round(($item['value'] / $totalStatus) * 100, 1) : 0, $donutData)) }},
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num ?? 0);
        }
    }">
        @if($lastUpdate)
            @php
                $importedAt = $lastUpdate->imported_at->setTimezone('Asia/Makassar');
                $conditionText = $importedAt->format('j F Y H:i') . ' WITA';
            @endphp
            <div class="stats-overview__last-update-badge-wrapper" x-data="{ showTooltip: false }">
                <div class="stats-overview__last-update-badge" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                    Update: {{ $conditionText }}
                </div>
                <div class="stats-overview__tooltip"
                     x-show="showTooltip"
                     x-transition:enter="stats-tooltip-enter-active"
                     x-transition:enter-start="stats-tooltip-enter-from"
                     x-transition:enter-end="stats-tooltip-enter-to"
                     x-transition:leave="stats-tooltip-leave-active"
                     x-transition:leave-start="stats-tooltip-leave-from"
                     x-transition:leave-end="stats-tooltip-leave-to">
                    <div class="stats-overview__tooltip-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <span>Data progress PCL dan PML akan di-update secara berkala dua kali sehari setiap pukul 07.00 WITA dan 20.00 WITA</span>
                </div>
            </div>
        @endif

        <div class="stats-overview__section-header">
            <h3 class="stats-overview__section-title">STATISTIK REALISASI DAN PENGERJAAN</h3>
        </div>

        <div class="stats-overview__main-grid">
            <div class="stats-overview__main-grid-left">
                {{-- Merged Card: Progress Total Realisasi & Total Tercacah --}}
                <div class="monitoring-stat-card monitoring-stat-card--overall stats-overview__metrics-card--merged">
                    <div class="monitoring-stat-card__content">
                        <div class="monitoring-stat-card__label-wrapper">
                            <span class="monitoring-stat-card__label">Progress Total Realisasi <strong>(submit + approve)</strong></span>
                        </div>
                        <div class="stats-overview__merged-stats">
                            <div class="stats-overview__merged-stat stats-overview__merged-stat--percentage">
                                <span class="stats-overview__merged-stat-value">{{ number_format($overallProgress, 2) }}%</span>
                                <span class="stats-overview__merged-stat-label">Progress</span>
                            </div>
                            <div class="stats-overview__merged-divider">
                                <div class="stats-overview__merged-divider-line"></div>
                                <div class="stats-overview__merged-divider-dot"></div>
                                <div class="stats-overview__merged-divider-line"></div>
                            </div>
                            <div class="stats-overview__merged-stat stats-overview__merged-stat--absolute">
                                <span class="stats-overview__merged-stat-value">{{ number_format($totalCompleted) }}</span>
                                <span class="stats-overview__merged-stat-label">Realisasi</span>
                            </div>
                        </div>
                        <div class="stats-overview__merged-footer">
                            <span class="stats-overview__merged-footer-text">dari total <strong>{{ number_format($totalTarget) }}</strong> target</span>
                        </div>
                        <div class="monitoring-stat-card__progress">
                            <div class="monitoring-stat-card__progress-track">
                                <div class="monitoring-stat-card__progress-bar" style="width: {{ $overallProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Merged Card: Progress & Total Pengerjaan --}}
                <div class="monitoring-stat-card monitoring-stat-card--purple stats-overview__metrics-card stats-overview__metrics-card--merged">
                    <div class="monitoring-stat-card__content">
                        <div class="monitoring-stat-card__label-wrapper">
                            <span class="monitoring-stat-card__label">Progress Pengerjaan <strong>(submit + approve + reject)</strong></span>
                        </div>
                        <div class="stats-overview__merged-stats">
                            <div class="stats-overview__merged-stat stats-overview__merged-stat--percentage">
                                <span class="stats-overview__merged-stat-value">{{ number_format($processingProgress, 2) }}%</span>
                                <span class="stats-overview__merged-stat-label">Progress</span>
                            </div>
                            <div class="stats-overview__merged-divider">
                                <div class="stats-overview__merged-divider-line"></div>
                                <div class="stats-overview__merged-divider-dot"></div>
                                <div class="stats-overview__merged-divider-line"></div>
                            </div>
                            <div class="stats-overview__merged-stat stats-overview__merged-stat--absolute">
                                <span class="stats-overview__merged-stat-value">{{ number_format($totalProcessed) }}</span>
                                <span class="stats-overview__merged-stat-label">Assignment Dikerjakan</span>
                            </div>
                        </div>
                        <div class="stats-overview__merged-footer">
                            <span class="stats-overview__merged-footer-text">dari total <strong>{{ number_format($totalTarget) }}</strong> target</span>
                        </div>
                        <div class="monitoring-stat-card__progress">
                            <div class="monitoring-stat-card__progress-track">
                                <div class="monitoring-stat-card__progress-bar" style="width: {{ $processingProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Donut Chart & Legend - Spanning 2 rows --}}
            <div class="stats-overview__donut-container"
                 x-data="donutChart({{ json_encode($chartJsData) }})">
                <div class="stats-overview__donut-header">
                    <span class="stats-overview__donut-header-title">Distribusi Assignment Berdasarkan Status</span>
                </div>
                <div class="stats-overview__donut-body">
                    <div class="stats-overview__donut-wrapper">
                        <canvas x-ref="chartCanvas"></canvas>
                    </div>
                    <div class="stats-overview__donut-legend">
                        <template x-for="(label, index) in chartData.labels" :key="index">
                            <div class="stats-overview__donut-legend-item"
                                 :class="{ 'active': activeSegment === index }"
                                 @mouseenter="activeSegment = index"
                                 @mouseleave="activeSegment = null">
                                <span class="stats-overview__donut-legend-dot"
                                      :style="`background-color: ${chartData.colors[index]}`"></span>
                                <span class="stats-overview__donut-legend-label" x-text="label"></span>
                                <span class="stats-overview__donut-legend-value"
                                      x-text="formatNumber(chartData.values[index])"></span>
                                <span class="stats-overview__donut-legend-percent"
                                      x-text="`(${getPercentage(index)}%)`"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
