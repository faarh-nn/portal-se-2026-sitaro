<x-layouts.app>
    <x-partials.navbar />

    {{-- Sub-navbar untuk navigasi section --}}
    <div class="monitoring-subnav-trigger">
        <div class="monitoring-subnav-trigger__circle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 8v8"></path>
                <path d="M8 12h8"></path>
            </svg>
        </div>
        <nav class="monitoring-subnav" x-data>
            <div class="monitoring-subnav__inner">
                <a href="#leaderboard-pcl" class="monitoring-subnav__item" data-label="Leaderboard PCL" :class="{ 'active': $store.monitoring.activeSection === 'leaderboard-pcl' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721" />
                    </svg>
                </a>
                <a href="#leaderboard-pml" class="monitoring-subnav__item" data-label="Leaderboard PML" :class="{ 'active': $store.monitoring.activeSection === 'leaderboard-pml' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.118-.439 1.557 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
                <a href="#progress-pcl" class="monitoring-subnav__item" data-label="Progress PCL" :class="{ 'active': $store.monitoring.activeSection === 'progress-pcl' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </a>
                <a href="#progress-pml" class="monitoring-subnav__item" data-label="Progress PML" :class="{ 'active': $store.monitoring.activeSection === 'progress-pml' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </a>
                <a href="#peta-kecamatan" class="monitoring-subnav__item" data-label="Peta Kecamatan" :class="{ 'active': $store.monitoring.activeSection === 'peta-kecamatan' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                    </svg>
                </a>
                <a href="#tabel-kecamatan" class="monitoring-subnav__item" data-label="Tabel Kecamatan" :class="{ 'active': $store.monitoring.activeSection === 'tabel-kecamatan' }">
                    <svg class="monitoring-subnav__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m17.25 0h-1.5M18 18.375v-1.5c0-.621-.504-1.125-1.125-1.125m-17.25 0h-1.5m1.5 0H6m15 0v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0c-.621 0-1.125.504-1.125 1.125M6 18.375v-1.5c0-.621.504-1.125 1.125-1.125m0 0h1.5" />
                    </svg>
                </a>
            </div>
        </nav>
    </div>

    <div class="monitoring-section__container">
        <x-sections.leaderboard-pcl :leaderboardData="$leaderboardData" :leaderboardDataPaginated="$leaderboardDataPaginated" :leaderboardLastUpdate="$leaderboardLastUpdate" />
        <x-sections.leaderboard-pml :pmlLeaderboardData="$pmlLeaderboardData" :pmlLeaderboardDataPaginated="$pmlLeaderboardDataPaginated" :pmlLeaderboardLastUpdate="$pmlLeaderboardLastUpdate" />
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