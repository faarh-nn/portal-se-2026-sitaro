<section class="timeline-section" id="timeline-kegiatan">
    <div class="timeline-section__container">
        <div class="timeline-section__header">
            <span class="timeline-section__eyebrow">Jadwal</span>
            <h2 class="timeline-section__title">Timeline Kegiatan Sensus Ekonomi 2026</h2>
            <p class="timeline-section__description">
                Tahapan dan jadwal pelaksanaan Sensus Ekonomi 2026 untuk mendapatkan data ekonomi yang komprehensif dan berkualitas.
            </p>
        </div>

        <div class="timeline-wrapper" x-data="{ activePhase: null }">
            <div class="timeline-path">
                <div class="timeline-path__line"></div>
                <div class="timeline-path__progress" :style="activePhase !== null ? 'width: ' + ((activePhase + 1) / 6 * 100) + '%' : 'width: 0%'"></div>
            </div>

            <div class="timeline-phases">
                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 0 }"
                     @click="activePhase = activePhase === 0 ? null : 0"
                     @mouseenter="activePhase = 0"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">Feb - Apr 2026</span>
                        <h3 class="timeline-phase__title">Persiapan</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 0" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>Sosialisasi</li>
                            <li>Pengadaan Perlengkapan</li>
                            <li>Updating SBR/Ground Check</li>
                            <li>KKD ke K/L/DI, Asosiasi, Kantor Pusat dan Pengelola Kawasan</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 1 }"
                     @click="activePhase = activePhase === 1 ? null : 1"
                     @mouseenter="activePhase = 1"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">Mei - Agu 2026</span>
                        <h3 class="timeline-phase__title">Pengorganisasian</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 1" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>KKD ke K/L/DI, Asosiasi, Kantor Pusat dan Pengelola Kawasan (lanjutan)</li>
                            <li>Ngibar (Ngisi Bareng)</li>
                            <li>Rekrutmen Petugas</li>
                            <li>Pelatihan Petugas Lapangan</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 2 }"
                     @click="activePhase = activePhase === 2 ? null : 2"
                     @mouseenter="activePhase = 2"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">Juni 2026</span>
                        <h3 class="timeline-phase__title">CAWI</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 2" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>Blast dan Pengisian Mandiri Link CAWI melalui Email/WA</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 3 }"
                     @click="activePhase = activePhase === 3 ? null : 3"
                     @mouseenter="activePhase = 3"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 3" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 3" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">15 Jun - 31 Agu 2026</span>
                        <h3 class="timeline-phase__title">Pendataan</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 3" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>Pendataan UB dan UM yang tidak memiliki email/WA</li>
                            <li>Pendataan UB dan UM yang memiliki email/WA namun tidak mengisi CAWI</li>
                            <li>Pendataan Door-to-Door (UB non-prelist, usaha mikro dan kecil, keluarga)</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 4 }"
                     @click="activePhase = activePhase === 4 ? null : 4"
                     @mouseenter="activePhase = 4"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 4" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">Sep - Nov 2026</span>
                        <h3 class="timeline-phase__title">Pemeriksaan</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 4" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>Pemeriksaan Akhir di Lapangan</li>
                            <li>Pengolahan</li>
                        </ul>
                    </div>
                </div>

                <div class="timeline-phase"
                     :class="{ 'is-active': activePhase === 5 }"
                     @click="activePhase = activePhase === 5 ? null : 5"
                     @mouseenter="activePhase = 5"
                     @mouseleave="activePhase = null">
                    <div class="timeline-phase__marker">
                        <div class="timeline-phase__dot">
                            <svg x-show="activePhase !== 5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            <svg x-show="activePhase === 5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="timeline-phase__content">
                        <span class="timeline-phase__date">Nov - Des 2026</span>
                        <h3 class="timeline-phase__title">Diseminasi</h3>
                    </div>
                    <div class="timeline-phase__details" x-show="activePhase === 5" x-collapse>
                        <ul class="timeline-phase__list">
                            <li>Penyusunan Laporan/Diseminasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
