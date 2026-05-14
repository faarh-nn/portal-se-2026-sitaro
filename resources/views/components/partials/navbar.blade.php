<nav class="navbar" id="navbar">
    <div class="navbar__container">
        <a href="#" class="navbar__brand">
            <img src="{{ asset('assets/img/logo-bps.png') }}" alt="Logo BPS" class="navbar__logo-img">
            <div class="navbar__brand-text">
                <p class="navbar__brand-line1">BPS KABUPATEN</p>
                <p class="navbar__brand-line2">KEPULAUAN SIAU TAGULANDANG BIARO</p>
            </div>
        </a>

        <div class="navbar__divider"></div>

        <a href="#" class="navbar__se-brand">
            <img src="{{ asset('assets/img/logo-se-2026.png') }}" alt="Logo SE" class="navbar__se-logo-img">
            <div class="navbar__se-text">
                <p class="navbar__se-line1">SENSUS</p>
                <p class="navbar__se-line2">EKONOMI</p>
                <p class="navbar__se-line3">2026</p>
            </div>
        </a>

        <div class="navbar__nav">
            <a href="#" class="navbar__link">Beranda</a>
            <a href="#" class="navbar__link">Monitoring Progress</a>
            <a href="#" class="navbar__link">Tanya AI</a>
            <a href="#" class="navbar__link">Laporan Lapangan</a>
        </div>

        <div class="navbar__actions">
            <button class="btn-secondary-pill">Masuk</button>
            <button class="btn-primary-pill">Daftar</button>
        </div>

        <button class="navbar__mobile-toggle" id="mobile-menu-btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div class="navbar__mobile-menu" id="mobile-menu">
        <div class="navbar__mobile-nav">
            <a href="#" class="navbar__mobile-link">Beranda</a>
            <a href="#" class="navbar__mobile-link">Tentang Sensus</a>
            <a href="#" class="navbar__mobile-link">Informasi</a>
            <a href="#" class="navbar__mobile-link">Kontak</a>
        </div>
    </div>
</nav>