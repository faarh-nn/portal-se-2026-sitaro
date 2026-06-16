<x-layouts.app title="Import Data - Portal SE 2026">
    <x-partials.navbar />

    <main class="admin-main">
        <div class="admin-container">
            <h2 class="admin-title">Import Data Monitoring</h2>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="admin-alert admin-alert--success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="admin-alert admin-alert--error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Info Cards --}}
            <div class="admin-stats-grid admin-stats-grid--4">
                <div class="admin-stat-card admin-stat-card--compact">
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $stats['total_officers'] }}</p>
                        <p class="admin-stat-label">Total Officer</p>
                    </div>
                </div>
                <div class="admin-stat-card admin-stat-card--compact">
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $stats['total_kecamatan'] }}</p>
                        <p class="admin-stat-label">Kecamatan</p>
                    </div>
                </div>
                <div class="admin-stat-card admin-stat-card--compact">
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $stats['total_pcl_records'] }}</p>
                        <p class="admin-stat-label">PCL Records</p>
                    </div>
                </div>
                <div class="admin-stat-card admin-stat-card--compact">
                    <div class="admin-stat-content">
                        <p class="admin-stat-value">{{ $stats['total_pml_records'] }}</p>
                        <p class="admin-stat-label">PML Records</p>
                    </div>
                </div>
            </div>

            {{-- Last Update Info --}}
            <div class="admin-info-row">
                <div class="admin-info-item">
                    <span class="admin-info-label">Mapping Terakhir:</span>
                    <span class="admin-info-value">
                        {{ $lastMappingUpdate ? $lastMappingUpdate->imported_at->format('d M Y, H:i') : 'Belum ada' }}
                    </span>
                </div>
                <div class="admin-info-item">
                    <span class="admin-info-label">Data Monitoring Terakhir:</span>
                    <span class="admin-info-value">
                        {{ $lastDataUpdate ? $lastDataUpdate->imported_at->format('d M Y, H:i') : 'Belum ada' }}
                    </span>
                </div>
            </div>

            {{-- Import Forms --}}
            <div class="admin-import-sections">
                {{-- Section 1: Mapping Files --}}
                <div class="admin-import-section">
                    <h3 class="admin-import-section__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        1. Import File Mapping
                    </h3>
                    <p class="admin-import-section__desc">Upload file mapping kecamatan dan nama officer terlebih dahulu.</p>

                    <div class="admin-import-forms">
                        <form action="{{ route('admin.import.kecamatan') }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
                            @csrf
                            <div class="admin-form-group">
                                <label class="admin-form-label">File Mapping Kecamatan</label>
                                <div class="admin-form-input-group">
                                    <input type="file" name="file" accept=".xlsx,.xls" class="admin-form-file" required>
                                    <button type="submit" class="btn-primary-pill">Upload</button>
                                </div>
                                <span class="admin-form-hint">Format: Kolom A = Kecamatan, Kolom B = Kode (6 digit)</span>
                            </div>
                        </form>

                        <form action="{{ route('admin.import.officer') }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
                            @csrf
                            <div class="admin-form-group">
                                <label class="admin-form-label">File Mapping Nama Officer</label>
                                <div class="admin-form-input-group">
                                    <input type="file" name="file" accept=".xlsx,.xls" class="admin-form-file" required>
                                    <button type="submit" class="btn-primary-pill">Upload</button>
                                </div>
                                <span class="admin-form-hint">Format: Kolom A = Nama Lengkap, Kolom B = Email</span>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Section 2: Monitoring Data --}}
                <div class="admin-import-section">
                    <div class="admin-import-section__header">
                        <h3 class="admin-import-section__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            2. Import Data Monitoring
                        </h3>
                        <form action="{{ route('admin.import.clean-latest') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus 2 data import terbaru beserta semua datanya?');">
                            @csrf
                            <button type="submit" class="btn-danger-pill">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                Bersihkan Data Import Terbaru
                            </button>
                        </form>
                    </div>
                    <p class="admin-import-section__desc">Upload file data monitoring PML dan PCL hasil scraping.</p>

                    <form action="{{ route('admin.import.monitoring') }}" method="POST" enctype="multipart/form-data" class="admin-monitoring-form">
                        @csrf
                        <div class="admin-form-row">
                            <div class="admin-form-group">
                                <label class="admin-form-label">File Data PML</label>
                                <input type="file" name="file_pml" accept=".xlsx,.xls" class="admin-form-file">
                            </div>
                            <div class="admin-form-group">
                                <label class="admin-form-label">File Data PCL</label>
                                <input type="file" name="file_pcl" accept=".xlsx,.xls" class="admin-form-file">
                            </div>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Tanggal Data</label>
                            <input type="date" name="data_date" class="admin-form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn-primary-pill">Import Data Monitoring</button>
                    </form>
                </div>
            </div>

            {{-- Import History --}}
            <div class="admin-history">
                <h3 class="admin-history__title">Riwayat Import</h3>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>File</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Rows</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($imports as $import)
                                <tr>
                                    <td>{{ $import->imported_at->format('d M Y, H:i') }}</td>
                                    <td class="admin-table__filename">{{ $import->file_name }}</td>
                                    <td>
                                        <span class="admin-badge admin-badge--{{ $import->type }}">
                                            {{ str_replace('_', ' ', $import->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="admin-badge admin-badge--status admin-badge--{{ $import->status }}">
                                            {{ $import->status }}
                                        </span>
                                    </td>
                                    <td>{{ $import->rows_imported ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="admin-table__empty">Belum ada data import</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <x-partials.footer />
</x-layouts.app>