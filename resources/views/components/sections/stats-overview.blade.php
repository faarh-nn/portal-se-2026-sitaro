<section class="stats-overview">
    <div class="stats-overview__card">
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

        <div class="stats-overview__progress">
            <div class="stats-overview__progress-header">
                <span class="stats-overview__progress-label">Progress keseluruhan saat ini</span>
                <span class="stats-overview__progress-value">{{ $overallProgress }}%</span>
            </div>
            <div class="stats-overview__progress-track">
                <div class="stats-overview__progress-bar" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>
    </div>
</section>
