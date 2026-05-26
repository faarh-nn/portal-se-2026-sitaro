<div class="monitoring-kecamatan-table-section">
    <h3 class="monitoring-kecamatan-table-section__title">Detail Progress per Kecamatan</h3>
    <div class="monitoring-kecamatan-table-wrapper">
        <table class="monitoring-kecamatan-table">
            <thead>
                <tr>
                    <th>Kecamatan</th>
                    <th>Progress</th>
                    <th>Tercacah</th>
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