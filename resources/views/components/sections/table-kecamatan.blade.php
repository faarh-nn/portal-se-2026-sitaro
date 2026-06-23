<div class="monitoring-kecamatan-table-section" id="tabel-kecamatan">
    <div class="monitoring-kecamatan-table-header">
        <h3 class="monitoring-kecamatan-table-section__title">Detail Progress per Kecamatan</h3>
        @if($pclLastUpdate)
            <span class="monitoring-kecamatan-table-section__last-update-wrapper" x-data="{ showTooltip: false }">
                <span class="monitoring-kecamatan-table-section__last-update" @mouseenter="showTooltip = true" @mouseleave="showTooltip = false">
                    Kondisi {{ $pclLastUpdate->imported_at->setTimezone('Asia/Makassar')->format('j F Y H:i') }} WITA
                </span>
                <span class="monitoring-kecamatan-table-section__last-update-tooltip"
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
                    <span>Data progress kecamatan akan di-update secara berkala dua kali sehari setiap pukul 07.00 WITA dan 20.00 WITA</span>
                </span>
            </span>
        @endif
    </div>
    <div class="monitoring-kecamatan-table-wrapper">
        <table class="monitoring-kecamatan-table">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>Progress</th>
                    <th>Tercacah (Submit + Approve)</th>
                    <th>Target</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progressData as $kecamatan => $data)
                    <tr>
                        <td class="monitoring-kecamatan-table__kecamatan">{{ $kecamatan }}</td>
                        <td>
                            @php
                                $barColor = $data['progress'] == 100 ? '#10B981' : ($data['progress'] >= 71 ? '#00a6fb' : ($data['progress'] >= 41 ? '#F59E0B' : '#EF4444'));
                            @endphp
                            <div class="monitoring-kecamatan-progress">
                                <div class="monitoring-kecamatan-progress__bar">
                                    <div class="monitoring-kecamatan-progress__fill" style="width: {{ $data['progress'] }}%; background-color: {{ $barColor }}"></div>
                                </div>
                                <span class="monitoring-kecamatan-progress__value">{{ $data['progress'] }}%</span>
                            </div>
                        </td>
                        <td class="monitoring-kecamatan-table__completed">{{ number_format($data['completed']) }}</td>
                        <td class="monitoring-kecamatan-table__target">{{ number_format($data['target']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>