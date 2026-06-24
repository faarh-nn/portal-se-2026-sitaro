<section class="stats-overview">
    <div class="stats-overview__card">
        @if($lastUpdate)
            @php
                $importedAt = $lastUpdate->imported_at->setTimezone('Asia/Makassar');
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
            <div class="stats-overview__last-update-badge-wrapper" x-data="{ showTooltip: false }">
                <div class="stats-overview__last-update-badge" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                    Data {{ $dayName }} kondisi {{ $conditionText }}
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
