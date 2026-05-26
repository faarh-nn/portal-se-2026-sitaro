<x-layouts.app>
    <x-partials.navbar />

    <x-sections.map-kecamatan
        :progressData="$progressData"
        :overallProgress="$overallProgress"
        :totalTarget="$totalTarget"
        :totalCompleted="$totalCompleted"
    />

    <div class="monitoring-section__container">
        <x-sections.table-kecamatan :progressData="$progressData" />
        <x-sections.progress-pcl :pclData="$pclData" :pclTotals="$pclTotals" :pclDataPaginated="$pclDataPaginated" :pmlList="$pmlList" />
        <x-sections.progress-pml :pmlData="$pmlData" :pmlTotals="$pmlTotals" />
    </div>

    <x-partials.footer />
</x-layouts.app>