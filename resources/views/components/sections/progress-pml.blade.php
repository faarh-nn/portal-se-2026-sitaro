<div class="pml-section">
    <div class="pml-section__header">
        <span class="pml-section__eyebrow">Monitoring PML</span>
        <h2 class="pml-section__title">Progress per PML (Petugas Pemeriksa Lapangan)</h2>
        <p class="pml-section__description">
            Monitoring progress pekerjaan lapangan setiap PML berdasarkan status objek kuesioner.
        </p>
    </div>

    <section class="pml-section__container">
        <div class="pml-stats-row">
            <div class="pml-stat-card pml-stat-card--open">
                <div class="pml-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                </div>
                <div class="pml-stat-card__content">
                    <span class="pml-stat-card__value">{{ number_format($pmlTotals['open']) }}</span>
                    <span class="pml-stat-card__label">Open</span>
                </div>
            </div>

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

            <div class="pml-stat-card pml-stat-card--pending">
                <div class="pml-stat-card__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="pml-stat-card__content">
                    <span class="pml-stat-card__value">{{ number_format($pmlTotals['pending']) }}</span>
                    <span class="pml-stat-card__label">Pending</span>
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
            <div class="pml-table-wrapper">
                <table class="pml-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama PML</th>
                            <th>Open</th>
                            <th>Submit</th>
                            <th>Reject</th>
                            <th>Pending</th>
                            <th>Approved (Progress)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pmlData as $index => $pml)
                            @php
                                $pmlBarColor = $pml['progress'] == 100 ? '#10B981' : ($pml['progress'] >= 71 ? '#00a6fb' : ($pml['progress'] >= 41 ? '#F59E0B' : '#EF4444'));
                            @endphp
                            <tr>
                                <td class="pml-table__no">{{ $index + 1 }}</td>
                                <td class="pml-table__name">{{ $pml['name'] }}</td>
                                <td class="pml-table__open">{{ $pml['open'] }}</td>
                                <td class="pml-table__submit">{{ $pml['submit'] }}</td>
                                <td class="pml-table__reject">{{ $pml['reject'] }}</td>
                                <td class="pml-table__pending">{{ $pml['pending'] }}</td>
                                <td class="pml-table__approved">
                                    <div class="pml-progress">
                                        <div class="pml-progress__bar">
                                            <div class="pml-progress__fill" style="width: {{ $pml['progress'] }}%; background-color: {{ $pmlBarColor }}"></div>
                                        </div>
                                        <span class="pml-progress__value">{{ $pml['approved'] }}/{{ $pml['target'] }} ({{ $pml['progress'] }}%)</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>