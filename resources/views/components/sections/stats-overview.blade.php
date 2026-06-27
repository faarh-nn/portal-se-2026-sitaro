<section class="stats-overview">
    <div class="stats-overview__card">
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
            <h3 class="stats-overview__section-title">Statistik Realisasi</h3>
        </div>

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
                    <span class="monitoring-stat-card__label">Realisasi</span>
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
                    <span class="monitoring-stat-card__label">Tercacah (Submit + Approve)</span>
                </div>
            </div>
        </div>

        {{-- Progress Bar Realisasi --}}
        <div class="stats-overview__progress stats-overview__progress--compact">
            <div class="stats-overview__progress-header">
                <span class="stats-overview__progress-label">Realisasi</span>
                <span class="stats-overview__progress-value stats-overview__progress-value--orange">{{ $overallProgress }}%</span>
            </div>
            <div class="stats-overview__progress-track">
                <div class="stats-overview__progress-bar stats-overview__progress-bar--orange" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>

        {{-- New: Progress Metrics Row --}}
        <div class="stats-overview__section-header">
            <h3 class="stats-overview__section-title stats-overview__section-title--purple">Statistik Pengerjaan</h3>
        </div>

        <div class="stats-overview__metrics-row">
            <div class="monitoring-stat-card monitoring-stat-card--purple">
                <div class="monitoring-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="monitoring-stat-card__content">
                    <div class="monitoring-stat-card__label-wrapper">
                        <span class="monitoring-stat-card__label">Progress pengerjaan</span>
                        <span class="monitoring-stat-card__badge">submit + reject + approve</span>
                    </div>
                    <span class="monitoring-stat-card__value">{{ number_format($processingProgress, 1) }}%</span>
                    <span class="monitoring-stat-card__subtitle"><strong>dari total {{ number_format($totalTarget) }} target</strong></span>
                </div>
            </div>
            <div class="monitoring-stat-card monitoring-stat-card--purple">
                <div class="monitoring-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <div class="monitoring-stat-card__content">
                    <div class="monitoring-stat-card__label-wrapper">
                        <span class="monitoring-stat-card__label">Assignment yang telah dikerjakan</span>
                        <span class="monitoring-stat-card__badge">submit + reject + approve</span>
                    </div>
                    <span class="monitoring-stat-card__value">{{ number_format($totalProcessed) }}</span>
                    <span class="monitoring-stat-card__subtitle"><strong>dari total {{ number_format($totalTarget) }} target</strong></span>
                </div>
            </div>
        </div>

        {{-- Progress Bar Progres Pengerjaan --}}
        <div class="stats-overview__progress">
            <div class="stats-overview__progress-header">
                <span class="stats-overview__progress-label">Progres Pengerjaan</span>
                <span class="stats-overview__progress-value stats-overview__progress-value--purple">{{ $processingProgress }}%</span>
            </div>
            <div class="stats-overview__progress-track">
                <div class="stats-overview__progress-bar stats-overview__progress-bar--purple" style="width: {{ $processingProgress }}%"></div>
            </div>
        </div>
    </div>
</section>
