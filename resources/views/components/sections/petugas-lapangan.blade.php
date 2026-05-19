<section class="petugas-section" id="petugas-lapangan">
    <div class="petugas-section__container">
        <div class="petugas-section__header">
            <span class="petugas-section__eyebrow">Tim Lapangan</span>
            <h2 class="petugas-section__title">Petugas Lapangan Sensus Ekonomi 2026 Kabupaten Kepulauan Siau Tagulandang Biaro</h2>
            <p class="petugas-section__description">
                Tim lapangan yang terlatih dan terverifikasi untuk melakukan pendataan ekonomi di seluruh wilayah Kabupaten Kepulauan Siau Tagulandang Biaro.
            </p>
        </div>

        <div class="petugas-stats"
             x-data="{
                showCounters: false,
                totalValue: 0,
                pmlValue: 0,
                pclValue: 0,
                animateValue(target, setter, duration = 1500) {
                    let start = 0;
                    const increment = target / (duration / 16);
                    const timer = setInterval(() => {
                        start += increment;
                        if (start >= target) {
                            this[setter] = target;
                            clearInterval(timer);
                        } else {
                            this[setter] = Math.floor(start);
                        }
                    }, 16);
                }
             }"
             x-intersect.once="
                showCounters = true;
                animateValue(75, 'totalValue');
                animateValue(12, 'pmlValue');
                animateValue(63, 'pclValue');
             ">
            <div class="petugas-stats__card">
                <div class="petugas-stats__number">
                    <span class="petugas-stats__value" x-text="totalValue">0</span>
                    <span class="petugas-stats__suffix">orang</span>
                </div>
                <p class="petugas-stats__label">Total Petugas Lapangan</p>
            </div>

            <div class="petugas-stats__breakdown">
                <div class="petugas-stats__breakdown-item">
                    <span class="petugas-stats__breakdown-value" x-text="pmlValue">0</span>
                    <span class="petugas-stats__breakdown-role">PML</span>
                    <span class="petugas-stats__breakdown-desc">Penyuluh Pemeriksa Lapangan</span>
                </div>
                <div class="petugas-stats__divider">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </div>
                <div class="petugas-stats__breakdown-item">
                    <span class="petugas-stats__breakdown-value" x-text="pclValue">0</span>
                    <span class="petugas-stats__breakdown-role">PCL</span>
                    <span class="petugas-stats__breakdown-desc">Petugas Pencacah Lapangan</span>
                </div>
            </div>
        </div>

        <div class="petugas-equipment">
            <h3 class="petugas-equipment__title">Perlengkapan Petugas</h3>
            <p class="petugas-equipment__subtitle">Perlengkapan resmi yang digunakan petugas saat melakukan pendataan</p>

            <div class="petugas-equipment__grid">
                <div class="petugas-equipment__card"
                     x-data="{ isFlipped: false }"
                     @mouseenter="isFlipped = true"
                     @mouseleave="isFlipped = false">
                    <div class="petugas-equipment__card-inner" :class="{ 'is-flipped': isFlipped }">
                        <div class="petugas-equipment__card-front">
                            <div class="petugas-equipment__card-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="4" width="18" height="12" rx="2"/>
                                    <circle cx="12" cy="9" r="2.5"/>
                                    <path d="M8 14c0 1.5 1.5 2 2 2s2-.5 2-2 1.5-2 2-2 2 .5 2 2"/>
                                    <path d="M15 8h.01"/>
                                    <path d="M9 8h.01"/>
                                </svg>
                            </div>
                            <h4 class="petugas-equipment__card-title">Tanda Pengenal</h4>
                            <p class="petugas-equipment__card-hint">Klik untuk info lebih lanjut</p>
                        </div>
                        <div class="petugas-equipment__card-back">
                            <h4 class="petugas-equipment__card-title">Tanda Pengenal</h4>
                            <p class="petugas-equipment__card-desc">
                                Diberikan kepada setiap petugas untuk dikenakan saat pelaksanaan lapangan. Berfungsi sebagai salah satu identitas resmi petugas Sensus Ekonomi 2026.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="petugas-equipment__card"
                     x-data="{ isFlipped: false }"
                     @mouseenter="isFlipped = true"
                     @mouseleave="isFlipped = false">
                    <div class="petugas-equipment__card-inner" :class="{ 'is-flipped': isFlipped }">
                        <div class="petugas-equipment__card-front">
                            <div class="petugas-equipment__card-icon">
                                <img src="{{ asset('assets/img/vest-se.svg') }}" alt="Rompi Sensus Ekonomi Icon">
                            </div>
                            <h4 class="petugas-equipment__card-title">Rompi</h4>
                            <p class="petugas-equipment__card-hint">Klik untuk info lebih lanjut</p>
                        </div>
                        <div class="petugas-equipment__card-back">
                            <h4 class="petugas-equipment__card-title">Rompi Sensus Ekonomi 2026</h4>
                            <p class="petugas-equipment__card-desc">
                                Rompi bertuliskan <strong>Sensus Ekonomi 2026</strong> merupakan identitas resmi petugas lapangan yang mudah dikenali masyarakat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="petugas-verification">
            <div class="petugas-verification__icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
            </div>
            <div class="petugas-verification__text">
                <h4 class="petugas-verification__title">Verifikasi Identitas Petugas</h4>
                <p class="petugas-verification__desc">
                    Setiap petugas dilengkapi tanda pengenal resmi. Jika ragu, Anda dapat memverifikasi identitas petugas melalui kantor BPS Kabupaten Kepulauan Siau Tagulandang Biaro
                </p>
            </div>
        </div>
    </div>
</section>