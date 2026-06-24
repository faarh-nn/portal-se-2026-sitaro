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

            {{-- Leaderboard Data Section --}}
            <div class="admin-import-section">
                <div class="admin-import-section__header">
                    <h3 class="admin-import-section__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20V10"></path>
                            <path d="M18 20V4"></path>
                            <path d="M6 20v-4"></path>
                        </svg>
                        Data Leaderboard
                    </h3>
                    <form action="{{ route('admin.import.clear-leaderboard') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data leaderboard?');">
                        @csrf
                        <button type="submit" class="btn-danger-pill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Bersihkan Data Leaderboard
                        </button>
                    </form>
                </div>
                <p class="admin-import-section__desc">Hapus semua data leaderboard PCL dan PML. Data monitoring tidak akan terpengaruh.</p>
            </div>

            {{-- PCL-PML Mapping Section --}}
            <div class="admin-import-section">
                <div class="admin-import-section__header">
                    <h3 class="admin-import-section__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm6 3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM7 10a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                        </svg>
                        Mapping PCL-PML
                    </h3>
                    <form action="{{ route('admin.import.clear-pcl-pml') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data mapping PCL-PML?');">
                        @csrf
                        <button type="submit" class="btn-danger-pill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Hapus Data PCL-PML
                        </button>
                    </form>
                </div>
                <p class="admin-import-section__desc">Upload file mapping antara PCL dan PML. Format: Kolom A = Email PCL, Kolom B = Email PML.</p>

                <form action="{{ route('admin.import.pcl-pml') }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
                    @csrf
                    <div class="admin-form-group">
                        <div class="admin-form-input-group">
                            <input type="file" name="file_pcl_pml" accept=".xlsx,.xls" class="admin-form-file" required>
                            <button type="submit" class="btn-primary-pill">Upload</button>
                        </div>
                        <span class="admin-form-hint">Format: Kolom A = Email PCL, Kolom B = Email PML</span>
                    </div>
                </form>
            </div>

            {{-- Assignment History Section --}}
            <div class="admin-import-section">
                <div class="admin-import-section__header">
                    <h3 class="admin-import-section__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Data History Assignment
                    </h3>
                    <form action="{{ route('admin.import.clear-assignment-history') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh data history assignment?');">
                        @csrf
                        <button type="submit" class="btn-danger-pill">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            Hapus Data History
                        </button>
                    </form>
                </div>
                <p class="admin-import-section__desc">
                    Upload file data history assignment untuk menghitung daily submit PCL dan PML.
                    <br>
                    <strong>Perhitungan Daily Submit:</strong>
                    <br>• <strong>PCL:</strong> Jumlah status mengandung "SUBMITTED" dalam range 1 hari terakhir
                    <br>• <strong>PML:</strong> Jumlah status mengandung "REJECT/REVOKE" + "APPROVE" dalam range 1 hari terakhir
                </p>

                <form action="{{ route('admin.import.assignment-history') }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
                    @csrf
                    <div class="admin-form-group">
                        <div class="admin-form-input-group">
                            <input type="file" name="file_assignment_history" accept=".xlsx,.xls" class="admin-form-file" required>
                            <button type="submit" class="btn-primary-pill">Upload Excel</button>
                        </div>
                        <span class="admin-form-hint">
                            Format: Kolom A = Email PML, Kolom B = Role PML, Kolom C = Email PCL, Kolom D = Role PCL,
                            Kolom E onwards = History_Status & History_Tanggal
                        </span>
                    </div>
                </form>
            </div>

            {{-- Daily Submits CSV Import Section --}}
            <div class="admin-import-section">
                <div class="admin-import-section__header">
                    <h3 class="admin-import-section__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Import Daily Submits via CSV
                    </h3>
                    <span class="admin-badge admin-badge--csv">Alternative</span>
                </div>
                <p class="admin-import-section__desc">
                    <strong>Untuk file besar:</strong> Gunakan script Python untuk konversi file Excel ke CSV,
                    kemudian upload CSV di sini. Lihat: <code>scripts/process_assignment_history.py</code>
                </p>

                <form action="{{ route('admin.import.daily-submits-csv') }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
                    @csrf
                    <div class="admin-form-group">
                        <div class="admin-form-input-row">
                            <div class="admin-form-input-item">
                                <label>File PCL CSV:</label>
                                <input type="file" name="file_pcl" accept=".csv,.txt" class="admin-form-file">
                            </div>
                            <div class="admin-form-input-item">
                                <label>File PML CSV:</label>
                                <input type="file" name="file_pml" accept=".csv,.txt" class="admin-form-file">
                            </div>
                            <div class="admin-form-input-item">
                                <label>Tanggal Data:</label>
                                <input type="date" name="data_date" class="admin-form-input" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="admin-form-input-group">
                            <button type="submit" class="btn-secondary-pill">Upload CSV</button>
                        </div>
                        <span class="admin-form-hint">
                            Minimal upload salah satu file CSV.
                        </span>
                    </div>
                </form>

                <div class="admin-import-section__csv-format">
                    <p><strong>Format CSV PCL:</strong></p>
                    <code>email,name,kecamatan,daily_submit,total_submit,target_met</code>

                    <p><strong>Format CSV PML:</strong></p>
                    <code>email,name,daily_reject,daily_approve,daily_combined,total_reject,total_approve,pcl_count,target_met</code>
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