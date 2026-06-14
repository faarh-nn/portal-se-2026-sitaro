<x-layouts.app title="Dashboard Admin - Portal SE 2026">
    <x-partials.navbar />

    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h2 class="admin-title">Dashboard Admin</h2>
                <a href="{{ route('admin.import') }}" class="btn-primary-pill">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Import Data
                </a>
            </div>

            <div class="admin-stats-grid">
                <div class="card card--stat admin-stat-card">
                    <div class="admin-stat-icon admin-stat-icon--blue">
                        <svg class="admin-stat-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $userCount }}</p>
                        <p class="admin-stat-label">Total Users</p>
                    </div>
                </div>

                <div class="card card--stat admin-stat-card">
                    <div class="admin-stat-icon admin-stat-icon--green">
                        <svg class="admin-stat-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $usahaCount }}</p>
                        <p class="admin-stat-label">Total Usaha</p>
                    </div>
                </div>

                <div class="card card--stat admin-stat-card">
                    <div class="admin-stat-icon admin-stat-icon--purple">
                        <svg class="admin-stat-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $adminCount }}</p>
                        <p class="admin-stat-label">Admin Users</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-partials.footer />
</x-layouts.app>