@foreach($pclPaginated as $index => $pcl)
    @php
        $pclBarColor = $pcl['submit_ratio'] == 100 ? '#10B981' : ($pcl['submit_ratio'] >= 71 ? '#00a6fb' : ($pcl['submit_ratio'] >= 41 ? '#F59E0B' : '#EF4444'));
        $rowNumber = ($pclPaginated->currentPage() - 1) * $pclPaginated->perPage() + $index + 1;
    @endphp
    <tr>
        <td class="pcl-table__no">{{ $rowNumber }}</td>
        <td class="pcl-table__name">{{ $pcl['name'] }}</td>
        <td class="pcl-table__open">{{ $pcl['open'] }}</td>
        <td class="pcl-table__submit">{{ $pcl['submit'] }}</td>
        <td class="pcl-table__reject">{{ $pcl['reject'] }}</td>
        <td class="pcl-table__pending">{{ $pcl['pending'] }}</td>
        <td class="pcl-table__pml">{{ $pcl['pml'] }}</td>
        <td class="pcl-table__submit-ratio">
            <div class="pcl-progress">
                <div class="pcl-progress__bar">
                    <div class="pcl-progress__fill" style="width: {{ $pcl['submit_ratio'] }}%; background-color: {{ $pclBarColor }}"></div>
                </div>
                <span class="pcl-progress__value">{{ $pcl['submit_ratio'] }}%</span>
            </div>
        </td>
    </tr>
@endforeach