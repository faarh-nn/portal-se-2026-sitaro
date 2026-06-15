@foreach($pmlLeaderboardPaginated as $index => $pml)
    @php
        $globalRank = ($pmlLeaderboardPaginated->currentPage() - 1) * $pmlLeaderboardPaginated->perPage() + $index + 1;
    @endphp
    <tr class="{{ $pml['target_met'] ? 'pml-leaderboard__row--met' : 'pml-leaderboard__row--not-met' }}">
        <td class="pml-leaderboard__rank-cell">
            <span class="pml-leaderboard__rank-number">{{ $globalRank }}</span>
        </td>
        <td class="pml-leaderboard__name-cell">
            <div class="pml-leaderboard__name-cell-content">
                <div class="pml-leaderboard__avatar pml-leaderboard__avatar--xs">
                    {{ strtoupper(substr($pml['name'], 0, 1)) }}
                </div>
                <span>{{ $pml['name'] }}</span>
            </div>
        </td>
        <td class="pml-leaderboard__pcl-cell">{{ $pml['pcl_count'] }}</td>
        <td class="pml-leaderboard__reject-cell">{{ $pml['daily_reject'] }}</td>
        <td class="pml-leaderboard__approve-cell">{{ $pml['daily_approve'] }}</td>
        <td class="pml-leaderboard__combined-cell">
            <span class="pml-leaderboard__combined-value">{{ $pml['daily_combined'] }}</span>
        </td>
        <td class="pml-leaderboard__target-cell">
            <span class="pml-leaderboard__target-badge-inline">≥{{ $pml['target_threshold'] }}</span>
        </td>
        <td class="pml-leaderboard__status-cell">
            <span class="pml-leaderboard__status-badge {{ $pml['target_met'] ? 'pml-leaderboard__status-badge--met' : 'pml-leaderboard__status-badge--not-met' }}">
                {{ $pml['target_met'] ? 'Terpenuhi' : 'Belum' }}
            </span>
        </td>
    </tr>
@endforeach
