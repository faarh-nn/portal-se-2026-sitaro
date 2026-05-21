@foreach($pclPaginated as $index => $pcl)
    @php
        $pclBarColor = $pcl['progress'] == 100 ? '#10B981' : ($pcl['progress'] >= 71 ? '#00a6fb' : ($pcl['progress'] >= 41 ? '#F59E0B' : '#EF4444'));
        $rowNumber = ($pclPaginated->currentPage() - 1) * $pclPaginated->perPage() + $index + 1;
    @endphp
    <tr>
        <td class="pcl-table__no">{{ $rowNumber }}</td>
        <td class="pcl-table__name">{{ $pcl['name'] }}</td>
        <td class="pcl-table__open">{{ $pcl['open'] }}</td>
        <td class="pcl-table__submit">{{ $pcl['submit'] }}</td>
        <td class="pcl-table__reject">{{ $pcl['reject'] }}</td>
        <td class="pcl-table__pending">{{ $pcl['pending'] }}</td>
        <td class="pcl-table__approved">
            <div class="pcl-progress">
                <div class="pcl-progress__bar">
                    <div class="pcl-progress__fill" style="width: {{ $pcl['progress'] }}%; background-color: {{ $pclBarColor }}"></div>
                </div>
                <span class="pcl-progress__value">{{ $pcl['approved'] }}/{{ $pcl['target'] }} ({{ $pcl['progress'] }}%)</span>
            </div>
        </td>
    </tr>
@endforeach