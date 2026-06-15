<x-layouts.app>
    <x-partials.navbar />

    <div class="monitoring-section__container">
        <x-sections.leaderboard-pcl :leaderboardData="$leaderboardData" :leaderboardDataPaginated="$leaderboardDataPaginated" :lastUpdate="$leaderboardLastUpdate" />
        <x-sections.leaderboard-pml :pmlLeaderboardData="$pmlLeaderboardData" :pmlLeaderboardDataPaginated="$pmlLeaderboardDataPaginated" :lastUpdate="$pmlLeaderboardLastUpdate" />
        <x-sections.progress-pcl :pclData="$pclData" :pclTotals="$pclTotals" :pclDataPaginated="$pclDataPaginated" :pmlList="$pmlList" :lastUpdate="$lastUpdate" />
        <x-sections.progress-pml :pmlData="$pmlData" :pmlTotals="$pmlTotals" :lastUpdate="$lastUpdate" />
    </div>

    <x-sections.map-kecamatan
        :progressData="$progressData"
        :overallProgress="$overallProgress"
        :totalTarget="$totalTarget"
        :totalCompleted="$totalCompleted"
    />

    <div class="monitoring-section__container">
        <x-sections.table-kecamatan :progressData="$progressData" :lastUpdate="$lastUpdate" />
    </div>

    <x-partials.footer />
</x-layouts.app>