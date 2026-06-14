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
            @auth
                @php
                    $userName = Auth::user()->name;
                    $nameParts = explode(' ', $userName);
                    $firstLetter = strtoupper(substr($nameParts[0], 0, 1));
                    $displayName = $nameParts[0];
                    if (count($nameParts) > 1) {
                        for ($i = 1; $i < count($nameParts); $i++) {
                            $displayName .= ' ' . strtoupper(substr($nameParts[$i], 0, 1)) . '.';
                        }
                    }
                    $role = Auth::user()->role === 'admin' ? 'Admin' : 'User';
                @endphp
                <div class="navbar__user-dropdown" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" class="navbar__user-btn" @click="open = !open" :class="{'is-active': open}">
                        <span class="navbar__user-avatar">{{ $firstLetter }}</span>
                        <span class="navbar__user-info">
                            <span class="navbar__user-name">{{ $displayName }}</span>
                            <span class="navbar__user-role">{{ $role }}</span>
                        </span>
                        <svg class="navbar__user-chevron" :class="{'is-rotated': open}" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="navbar__dropdown-menu" x-show="open" x-transition>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="navbar__dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-secondary-pill">Masuk</a>
                <button class="btn-primary-pill">Daftar</button>
            @endauth
        </div>

        <div class="navbar__mobile-controls">
            @auth
                @php
                    $userName = Auth::user()->name;
                    $firstLetter = strtoupper(substr($userName, 0, 1));
                @endphp
                <div class="navbar__mobile-user" x-data="{ open: false }" @click.away="open = false">
                    <button type="button" class="navbar__mobile-avatar-btn" @click="open = !open" :class="{'is-active': open}">
                        <span class="navbar__user-avatar navbar__user-avatar--mobile">{{ $firstLetter }}</span>
                    </button>
                    <div class="navbar__dropdown-menu navbar__dropdown-menu--mobile" x-show="open" x-transition>
                        <div class="navbar__dropdown-header">
                            <span class="navbar__user-avatar">{{ $firstLetter }}</span>
                            <div class="navbar__dropdown-user-info">
                                @php
                                    $nameParts = explode(' ', Auth::user()->name);
                                    $displayName = $nameParts[0];
                                    if (count($nameParts) > 1) {
                                        for ($i = 1; $i < count($nameParts); $i++) {
                                            $displayName .= ' ' . strtoupper(substr($nameParts[$i], 0, 1)) . '.';
                                        }
                                    }
                                @endphp
                                <span class="navbar__dropdown-user-name">{{ $displayName }}</span>
                                <span class="navbar__dropdown-user-role">{{ Auth::user()->role === 'admin' ? 'Admin' : 'User' }}</span>
                            </div>
                        </div>
                        <div class="navbar__dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="navbar__dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            @guest
                <button class="btn-primary-pill navbar__mobile-daftar">Daftar</button>
            @endguest
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
            @auth
                <div class="navbar__mobile-logged-in">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Logged in as {{ Auth::user()->name }}</span>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-secondary-pill navbar__mobile-btn">Masuk</a>
                <button class="btn-primary-pill navbar__mobile-btn">Daftar</button>
            @endauth
        </div>
    </div>
</nav>