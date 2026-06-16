<x-layouts.app>
    <x-partials.navbar />
    <x-sections.hero
        :overallProgress="$overallProgress"
        :totalTarget="$totalTarget"
        :totalCompleted="$totalCompleted"
    />
    <x-sections.statistics-usaha-sbr
        :totalUsaha="$totalUsaha"
        :statsBySkala="$statsBySkala"
        :statsByKecamatan="$statsByKecamatan"
        :statsByDesa="$statsByDesa"
    />
    <x-sections.map-usaha :usahaGmaps="$usahaGmaps" :statsGmaps="$statsGmaps" :allUsahaGmaps="$allUsahaGmaps" :kategoriOptions="$kategoriOptions" />
    <x-sections.tujuan-se />
    <x-sections.cakupan-kbli />
    <x-sections.timeline-kegiatan />
    <x-sections.petugas-lapangan />
    <x-partials.footer />
</x-layouts.app>