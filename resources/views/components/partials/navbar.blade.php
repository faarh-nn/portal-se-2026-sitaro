<nav class="navbar" id="navbar">
    <div class="navbar__container">
        <div class="navbar__brand-group">
            <a href="https://sitarokab.bps.go.id/id" class="navbar__brand" target="_blank">
                <img src="{{ asset('assets/img/logo-bps.svg') }}" alt="Logo BPS" class="navbar__logo-img">
                <div class="navbar__brand-text">
                    <p class="navbar__brand-line1">BPS KABUPATEN</p>
                    <p class="navbar__brand-line2">KEPULAUAN SIAU TAGULANDANG BIARO</p>
                </div>
            </a>

            <div class="navbar__divider"></div>

            <a href="#" class="navbar__se-brand">
                <img src="{{ asset('assets/img/logo-se-2026.svg') }}" alt="Logo SE" class="navbar__se-logo-img">
                <div class="navbar__se-text">
                    <p class="navbar__se-line1">SENSUS</p>
                    <p class="navbar__se-line2">EKONOMI</p>
                    <p class="navbar__se-line3">2026</p>
                </div>
            </a>
        </div>

        <div class="navbar__nav">
            <a href="{{ url('/') }}" class="navbar__link{{ request()->is('/') ? ' navbar__link--active' : '' }}">Beranda</a>
            <a href="{{ route('monitoring') }}" class="navbar__link{{ request()->is('monitoring') ? ' navbar__link--active' : '' }}">Monitoring Progress</a>
            <a href="#" class="navbar__link">Tanya AI</a>
            <a href="#" class="navbar__link">Laporan Lapangan</a>
        </div>

        <div class="navbar__actions">
            <button class="btn-secondary-pill">Masuk</button>
            <button class="btn-primary-pill">Daftar</button>
        </div>

        <div class="navbar__mobile-controls">
            <button class="btn-primary-pill navbar__mobile-daftar">Daftar</button>
            <button class="navbar__mobile-toggle" id="mobile-menu-btn" aria-label="Toggle menu">
                <span class="hamburger">
                    <span class="hamburger__line"></span>
                    <span class="hamburger__line"></span>
                    <span class="hamburger__line"></span>
                </span>
            </button>
        </div>
    </div>

    <div class="navbar__mobile-menu" id="mobile-menu">
        <div class="navbar__mobile-nav">
            <a href="{{ url('/') }}" class="navbar__mobile-link{{ request()->is('/') ? ' navbar__mobile-link--active' : '' }}">Beranda</a>
            <a href="{{ route('monitoring') }}" class="navbar__mobile-link{{ request()->is('monitoring') ? ' navbar__mobile-link--active' : '' }}">Monitoring Progress</a>
            <a href="#" class="navbar__mobile-link">Tanya AI</a>
            <a href="#" class="navbar__mobile-link">Laporan Lapangan</a>
        </div>
        <div class="navbar__mobile-actions">
            <button class="btn-secondary-pill navbar__mobile-btn">Masuk</button>
            <button class="btn-primary-pill navbar__mobile-btn">Daftar</button>
        </div>
    </div>
</nav>