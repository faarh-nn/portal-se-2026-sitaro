@foreach($leaderboardPaginated as $index => $pcl)
    @php
        $globalRank = ($leaderboardPaginated->currentPage() - 1) * $leaderboardPaginated->perPage() + $index + 1;
    @endphp
    <tr class="{{ $pcl['target_met'] ? 'leaderboard-pcl__row--met' : 'leaderboard-pcl__row--not-met' }}">
        <td class="leaderboard-pcl__rank-cell">
            <span class="leaderboard-pcl__rank-number">{{ $globalRank }}</span>
        </td>
        <td class="leaderboard-pcl__name-cell">
            <div class="leaderboard-pcl__name-cell-content">
                <div class="leaderboard-pcl__avatar leaderboard-pcl__avatar--xs">
                    {{ strtoupper(substr($pcl['name'], 0, 1)) }}
                </div>
                <span>{{ $pcl['name'] }}</span>
            </div>
        </td>
        <td class="leaderboard-pcl__kecamatan-cell">{{ $pcl['kecamatan_string'] }}</td>
        <td class="leaderboard-pcl__submit-cell">
            <span class="leaderboard-pcl__submit-value">{{ $pcl['daily_submit'] }}</span>
        </td>
        <td class="leaderboard-pcl__status-cell">
            <span class="leaderboard-pcl__status-badge {{ $pcl['target_met'] ? 'leaderboard-pcl__status-badge--met' : 'leaderboard-pcl__status-badge--not-met' }}">
                {{ $pcl['target_met'] ? 'Terpenuhi' : 'Belum' }}
            </span>
        </td>
    </tr>
@endforeach
