<x-layouts.app>
    <x-partials.navbar />

    <x-sections.map-kecamatan
        :progressData="$progressData"
        :overallProgress="$overallProgress"
        :totalTarget="$totalTarget"
        :totalCompleted="$totalCompleted"
    />

    <div class="monitoring-section__container">
        <x-sections.table-kecamatan :progressData="$progressData" :lastUpdate="$lastUpdate" />
        <x-sections.progress-pcl :pclData="$pclData" :pclTotals="$pclTotals" :pclDataPaginated="$pclDataPaginated" :pmlList="$pmlList" :lastUpdate="$lastUpdate" />
        <x-sections.progress-pml :pmlData="$pmlData" :pmlTotals="$pmlTotals" :lastUpdate="$lastUpdate" />
    </div>

    <x-partials.footer />
</x-layouts.app>