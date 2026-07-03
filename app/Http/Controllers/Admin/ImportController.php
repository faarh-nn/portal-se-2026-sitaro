<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KecamatanMapping;
use App\Models\KecamatanProgress;
use App\Models\MonitoringImport;
use App\Models\OfficerMapping;
use App\Models\PclDailySubmit;
use App\Models\PclPml;
use App\Models\PclProgress;
use App\Models\PclTotalAssignment;
use App\Models\PmlDailySubmit;
use App\Models\PmlProgress;
use App\Models\PmlTotalAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportController extends Controller
{
    /**
     * Display the import page.
     */
    public function index(): View
    {
        $imports = MonitoringImport::orderByDesc('imported_at')
            ->limit(20)
            ->get();

        $lastMappingUpdate = MonitoringImport::whereIn('type', ['mapping_kecamatan', 'mapping_officer'])
            ->where('status', 'completed')
            ->orderByDesc('imported_at')
            ->first();

        $lastDataUpdate = MonitoringImport::whereIn('type', ['data_pml', 'data_pcl'])
            ->where('status', 'completed')
            ->orderByDesc('imported_at')
            ->first();

        $stats = [
            'total_officers' => OfficerMapping::count(),
            'total_kecamatan' => KecamatanMapping::count(),
            'total_pcl_records' => PclProgress::count(),
            'total_pml_records' => PmlProgress::count(),
        ];

        return view('admin.import', compact(
            'imports',
            'lastMappingUpdate',
            'lastDataUpdate',
            'stats'
        ));
    }

    /**
     * Import kecamatan mapping from Excel.
     */
    public function importKecamatanMapping(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $import = MonitoringImport::create([
            'file_name' => $request->file('file')->getClientOriginalName(),
            'type' => 'mapping_kecamatan',
            'status' => 'processing',
            'imported_by' => auth()->id(),
            'imported_at' => now(),
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $count = 0;
            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                KecamatanMapping::updateOrCreate(
                    ['kode' => (string) $row[1]],
                    ['kecamatan' => trim($row[0])]
                );
                $count++;
            }

            $import->markCompleted($count);

            return redirect()->back()->with('success', "Berhasil import {$count} data kecamatan.");
        } catch (\Exception $e) {
            Log::error('Kecamatan mapping import failed', [
                'import_id' => $import->id,
                'error' => $e->getMessage(),
            ]);

            $import->markFailed($e->getMessage());

            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Import officer mapping from Excel.
     */
    public function importOfficerMapping(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $import = MonitoringImport::create([
            'file_name' => $request->file('file')->getClientOriginalName(),
            'type' => 'mapping_officer',
            'status' => 'processing',
            'imported_by' => auth()->id(),
            'imported_at' => now(),
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $count = 0;
            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    continue;
                }

                $type = strtoupper(trim($row[2]));
                if (! in_array($type, ['PML', 'PCL'])) {
                    continue;
                }

                OfficerMapping::updateOrCreate(
                    ['email' => strtolower(trim($row[1]))],
                    [
                        'name' => trim($row[0]),
                        'type' => $type,
                    ]
                );
                $count++;
            }

            $import->markCompleted($count);

            return redirect()->back()->with('success', "Berhasil import {$count} data officer.");
        } catch (\Exception $e) {
            Log::error('Officer mapping import failed', [
                'import_id' => $import->id,
                'error' => $e->getMessage(),
            ]);

            $import->markFailed($e->getMessage());

            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Import monitoring data (PML and PCL) from Excel.
     */
    public function importMonitoringData(Request $request)
    {
        $request->validate([
            'file_pml' => 'required_without_all:file_pcl|file|mimes:xlsx,xls|max:10240',
            'file_pcl' => 'required_without_all:file_pml|file|mimes:xlsx,xls|max:10240',
            'data_date' => 'required|date',
        ]);

        $dataDate = $request->input('data_date');
        $totalRows = 0;

        DB::beginTransaction();

        try {
            // Import PML data
            if ($request->hasFile('file_pml')) {
                $pmlImport = MonitoringImport::create([
                    'file_name' => $request->file('file_pml')->getClientOriginalName(),
                    'type' => 'data_pml',
                    'status' => 'processing',
                    'imported_by' => auth()->id(),
                    'imported_at' => now(),
                ]);

                $count = $this->processPmlData($request->file('file_pml'), $pmlImport, $dataDate);
                $pmlImport->markCompleted($count);
                $totalRows += $count;
            }

            // Import PCL data
            if ($request->hasFile('file_pcl')) {
                $pclImport = MonitoringImport::create([
                    'file_name' => $request->file('file_pcl')->getClientOriginalName(),
                    'type' => 'data_pcl',
                    'status' => 'processing',
                    'imported_by' => auth()->id(),
                    'imported_at' => now(),
                ]);

                $count = $this->processPclData($request->file('file_pcl'), $pclImport, $dataDate);
                $pclImport->markCompleted($count);
                $totalRows += $count;

                // Generate kecamatan progress from PCL data
                $this->generateKecamatanProgress($pclImport->id, $dataDate);
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil import {$totalRows} data monitoring.");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Monitoring data import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Process PML data from Excel.
     */
    private function processPmlData($file, MonitoringImport $import, string $dataDate): int
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $count = 0;
        $aggregatedData = [];
        $totalAssignments = [];

        // First pass: collect unique email + total_assignment, and sum status columns
        foreach (array_slice($rows, 1) as $row) {
            if (empty($row[0])) {
                continue;
            }

            $email = strtolower(trim($row[0]));
            $totalAssignment = (int) ($row[1] ?? 0);

            // Store unique total_assignment per email (take first value)
            if (! isset($totalAssignments[$email])) {
                $totalAssignments[$email] = $totalAssignment;
            }

            if (! isset($aggregatedData[$email])) {
                $aggregatedData[$email] = [
                    'email' => $email,
                    'open' => 0,
                    'draft' => 0,
                    'submit' => 0,
                    'approve' => 0,
                    'reject' => 0,
                    'completed' => 0,
                ];
            }

            // Sum status columns only
            $aggregatedData[$email]['open'] += (int) ($row[3] ?? 0);
            $aggregatedData[$email]['draft'] += (int) ($row[4] ?? 0);
            $aggregatedData[$email]['submit'] += (int) ($row[5] ?? 0);
            $aggregatedData[$email]['approve'] += (int) ($row[6] ?? 0);
            $aggregatedData[$email]['reject'] += (int) ($row[7] ?? 0);
            $aggregatedData[$email]['completed'] += (int) ($row[8] ?? 0);
        }

        // Save total assignments to separate table
        foreach ($totalAssignments as $email => $total) {
            PmlTotalAssignment::updateOrCreate(
                ['email' => $email],
                ['total_assignment' => $total]
            );
        }

        // Get officer names
        $officerNames = OfficerMapping::whereIn('email', array_keys($aggregatedData))
            ->pluck('name', 'email')
            ->toArray();

        // Save aggregated data
        foreach ($aggregatedData as $email => $data) {
            $data['name'] = $officerNames[$email] ?? null;
            $data['import_id'] = $import->id;
            $data['data_date'] = $dataDate;
            $data['created_at'] = now();
            $data['updated_at'] = now();

            PmlProgress::create($data);
            $count++;
        }

        return $count;
    }

    /**
     * Process PCL data from Excel.
     */
    private function processPclData($file, MonitoringImport $import, string $dataDate): int
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Get kecamatan mappings for block_id to kecamatan conversion
        $kecamatanMappings = KecamatanMapping::pluck('kecamatan', 'kode')->toArray();

        // Get officer mappings for name lookup
        $officerNames = OfficerMapping::pluck('name', 'email')->toArray();

        $count = 0;
        $aggregatedData = [];
        $totalAssignments = [];

        // First pass: collect unique email + total_assignment, and sum status columns by email + kecamatan
        foreach (array_slice($rows, 1) as $row) {
            if (empty($row[0])) {
                continue;
            }

            $email = strtolower(trim($row[0]));
            $totalAssignment = (int) ($row[1] ?? 0);
            $blockId = (string) ($row[2] ?? '');
            $kode7 = substr($blockId, 0, 7);

            // Store unique total_assignment per email (take first value)
            if (! isset($totalAssignments[$email])) {
                $totalAssignments[$email] = $totalAssignment;
            }

            // Find matching kecamatan from mapping
            $kecamatan = null;
            foreach ($kecamatanMappings as $mapKode => $mapKecamatan) {
                if (str_starts_with($kode7, $mapKode)) {
                    $kecamatan = $mapKecamatan;
                    break;
                }
            }

            // Skip if no kecamatan found
            if ($kecamatan === null) {
                continue;
            }

            // Key by email + kecamatan combination
            $key = $email.'|'.$kecamatan;

            if (! isset($aggregatedData[$key])) {
                $aggregatedData[$key] = [
                    'email' => $email,
                    'name' => $officerNames[$email] ?? null,
                    'kecamatan' => $kecamatan,
                    'open' => 0,
                    'draft' => 0,
                    'submit' => 0,
                    'approve' => 0,
                    'reject' => 0,
                    'completed' => 0,
                ];
            }

            // Sum status columns only per email + kecamatan
            $aggregatedData[$key]['open'] += (int) ($row[3] ?? 0);
            $aggregatedData[$key]['draft'] += (int) ($row[4] ?? 0);
            $aggregatedData[$key]['submit'] += (int) ($row[5] ?? 0);
            $aggregatedData[$key]['approve'] += (int) ($row[6] ?? 0);
            $aggregatedData[$key]['reject'] += (int) ($row[7] ?? 0);
            $aggregatedData[$key]['completed'] += (int) ($row[8] ?? 0);
        }

        // Save total assignments to separate table
        foreach ($totalAssignments as $email => $total) {
            PclTotalAssignment::updateOrCreate(
                ['email' => $email],
                ['total_assignment' => $total]
            );
        }

        // Save aggregated data
        foreach ($aggregatedData as $data) {
            $data['import_id'] = $import->id;
            $data['data_date'] = $dataDate;
            $data['created_at'] = now();
            $data['updated_at'] = now();

            PclProgress::create($data);
            $count++;
        }

        return $count;
    }

    /**
     * Generate kecamatan progress from the latest PCL data (by import_id).
     */
    private function generateKecamatanProgress(int $importId, string $dataDate): void
    {
        // Aggregate only the latest imported PCL data by kecamatan
        // import_id alone is sufficient since each upload creates a new unique import_id
        $kecamatanData = PclProgress::where('import_id', $importId)
            ->selectRaw('kecamatan, SUM(open) as open, SUM(draft) as draft, SUM(submit) as submit, SUM(approve) as approve, SUM(reject) as reject, SUM(completed) as completed')
            ->groupBy('kecamatan')
            ->get();

        foreach ($kecamatanData as $data) {
            if (empty($data->kecamatan)) {
                continue;
            }

            // Get kode from kecamatan mapping
            $kode = KecamatanMapping::where('kecamatan', $data->kecamatan)->value('kode');

            // Calculate total_assignment from sum of status columns
            $totalAssignment = ($data->open ?? 0) + ($data->draft ?? 0) + ($data->submit ?? 0) + ($data->approve ?? 0) + ($data->reject ?? 0) + ($data->completed ?? 0);

            KecamatanProgress::create([
                'kecamatan' => $data->kecamatan,
                'kode' => $kode,
                'total_assignment' => $totalAssignment,
                'open' => $data->open,
                'draft' => $data->draft,
                'submit' => $data->submit,
                'approve' => $data->approve,
                'reject' => $data->reject,
                'completed' => $data->completed,
                'import_id' => $importId,
                'data_date' => $dataDate,
            ]);
        }
    }

    /**
     * Clean the latest import data (2 most recent imports and their related data).
     */
    public function cleanLatestImport(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        // Get 2 latest import records
        $latestImports = MonitoringImport::orderByDesc('id')->take(2)->get();

        if ($latestImports->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data import yang bisa dihapus.');
        }

        // Get import IDs and their data_dates for cleanup
        $latestImportIds = $latestImports->pluck('id')->toArray();
        $latestDataDates = $latestImports->pluck('data_date')->filter()->unique()->toArray();

        // Also get import_ids of data_pcl and data_pml imports among the latest ones
        $pclImportId = $latestImports->where('type', 'data_pcl')->max('id');
        $pmlImportId = $latestImports->where('type', 'data_pml')->max('id');

        DB::beginTransaction();

        try {
            // 1. Delete monitoring_imports first (to get correct import_ids before deletion)
            MonitoringImport::whereIn('id', $latestImportIds)->delete();

            // 2. Delete kecamatan_progress with the deleted PCL import_id
            if ($pclImportId) {
                KecamatanProgress::where('import_id', $pclImportId)->delete();
            }

            // 3. Delete pcl_progress with the deleted PCL import_id
            if ($pclImportId) {
                PclProgress::where('import_id', $pclImportId)->delete();
            }

            // 4. Delete pml_progress with the deleted PML import_id
            if ($pmlImportId) {
                PmlProgress::where('import_id', $pmlImportId)->delete();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data import terbaru berhasil dibersihkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal membersihkan data: '.$e->getMessage());
        }
    }

    /**
     * Delete all data (for testing/reset).
     */
    public function clearData(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        PclProgress::truncate();
        PmlProgress::truncate();
        KecamatanProgress::truncate();
        MonitoringImport::truncate();
        OfficerMapping::truncate();
        KecamatanMapping::truncate();
        PclDailySubmit::truncate();
        PmlDailySubmit::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
    }

    /**
     * Clear leaderboard data (pcl_daily_submits and pml_daily_submits).
     */
    public function clearLeaderboardData(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        PclDailySubmit::truncate();
        PmlDailySubmit::truncate();

        return redirect()->back()->with('success', 'Data leaderboard berhasil dibersihkan.');
    }

    /**
     * Import PCL-PML mapping from Excel.
     */
    public function importPclPmlMapping(Request $request)
    {
        $request->validate([
            'file_pcl_pml' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $import = MonitoringImport::create([
            'file_name' => $request->file('file_pcl_pml')->getClientOriginalName(),
            'type' => 'mapping_pcl_pml',
            'status' => 'processing',
            'imported_by' => auth()->id(),
            'imported_at' => now(),
        ]);

        try {
            $file = $request->file('file_pcl_pml');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $count = 0;
            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0]) || empty($row[1])) {
                    continue;
                }

                PclPml::updateOrCreate(
                    ['pcl_email' => strtolower(trim($row[0]))],
                    ['pml_email' => strtolower(trim($row[1]))]
                );
                $count++;
            }

            $import->markCompleted($count);

            return redirect()->back()->with('success', "Berhasil import {$count} data mapping PCL-PML.");
        } catch (\Exception $e) {
            Log::error('PCL-PML mapping import failed', [
                'import_id' => $import->id,
                'error' => $e->getMessage(),
            ]);

            $import->markFailed($e->getMessage());

            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Clear PCL-PML mapping data.
     */
    public function clearPclPmlData(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        PclPml::truncate();

        return redirect()->back()->with('success', 'Data mapping PCL-PML berhasil dihapus.');
    }

    /**
     * Clear kecamatan mapping data.
     */
    public function clearKecamatanMapping(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        KecamatanMapping::truncate();

        return redirect()->back()->with('success', 'Data mapping kecamatan berhasil dihapus.');
    }

    /**
     * Clear officer mapping data.
     */
    public function clearOfficerMapping(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        OfficerMapping::truncate();

        return redirect()->back()->with('success', 'Data mapping officer berhasil dihapus.');
    }

    /**
     * Import daily submits from CSV files.
     */
    public function importDailySubmitsCsv(Request $request)
    {
        $request->validate([
            'file_pcl' => 'nullable|file|mimes:csv,txt|max:20480',
            'file_pml' => 'nullable|file|mimes:csv,txt|max:20480',
            'data_date' => 'nullable|date',
        ]);

        if (! $request->hasFile('file_pcl') && ! $request->hasFile('file_pml')) {
            return redirect()->back()->with('error', 'Pilih setidaknya satu file CSV untuk diimport.');
        }

        $dataDate = $request->input('data_date') ?? now()->format('Y-m-d');
        $pclCount = 0;
        $pmlCount = 0;

        DB::beginTransaction();

        try {
            // Get officer names for lookup
            $officerNames = OfficerMapping::pluck('name', 'email')->toArray();

            // Get PCL count per PML from pcl_pml mapping
            $pclCounts = PclPml::select('pml_email', DB::raw('COUNT(*) as count'))
                ->groupBy('pml_email')
                ->pluck('count', 'pml_email')
                ->toArray();

            // Get kecamatans per PCL from PclProgress (master data)
            $kecamatansByEmail = PclProgress::select('email', 'kecamatan')
                ->distinct()
                ->get()
                ->groupBy('email')
                ->map(fn ($items) => $items->pluck('kecamatan')->filter()->unique()->values()->toArray());

            // Import PCL data
            if ($request->hasFile('file_pcl')) {
                $pclCount = $this->importCsvToPclDailySubmits(
                    $request->file('file_pcl'),
                    $dataDate,
                    $officerNames,
                    $kecamatansByEmail
                );
            }

            // Import PML data
            if ($request->hasFile('file_pml')) {
                $pmlCount = $this->importCsvToPmlDailySubmits(
                    $request->file('file_pml'),
                    $dataDate,
                    $officerNames,
                    $pclCounts
                );
            }

            DB::commit();

            $message = "Berhasil import {$pclCount} data PCL dan {$pmlCount} data PML.";

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Daily submits CSV import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Gagal import: '.$e->getMessage());
        }
    }

    /**
     * Import CSV to PCL daily submits.
     */
    private function importCsvToPclDailySubmits(
        $file,
        string $dataDate,
        array $officerNames,
        $kecamatansByEmail
    ): int {
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $email = strtolower(trim($row[0] ?? ''));
            if (empty($email)) {
                continue;
            }

            $dailySubmit = (int) ($row[3] ?? 0);
            $totalSubmit = (int) ($row[4] ?? $dailySubmit);
            $kecamatans = $kecamatansByEmail->get($email, []);

            PclDailySubmit::updateOrCreate(
                ['email' => $email, 'data_date' => $dataDate],
                [
                    'name' => $officerNames[$email] ?? null,
                    'kecamatan' => $kecamatans,
                    'daily_submit' => $dailySubmit,
                    'total_submit' => $totalSubmit,
                    'target_met' => $dailySubmit >= 10,
                ]
            );

            $count++;
        }

        fclose($handle);

        return $count;
    }

    /**
     * Import CSV to PML daily submits.
     */
    private function importCsvToPmlDailySubmits(
        $file,
        string $dataDate,
        array $officerNames,
        array $pclCounts
    ): int {
        $handle = fopen($file->getPathname(), 'r');
        fgetcsv($handle);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $email = strtolower(trim($row[0] ?? ''));
            if (empty($email)) {
                continue;
            }

            $dailyReject = (int) ($row[2] ?? 0);
            $dailyApprove = (int) ($row[3] ?? 0);
            $dailyCombined = $dailyReject + $dailyApprove;
            $totalReject = (int) ($row[5] ?? $dailyReject);
            $totalApprove = (int) ($row[6] ?? $dailyApprove);
            $pclCount = $pclCounts[$email] ?? (int) ($row[7] ?? 0);

            // Calculate target threshold: 5 * pcl_count
            $targetThreshold = 5 * $pclCount;
            $targetMet = $pclCount > 0 && $dailyCombined >= $targetThreshold;

            PmlDailySubmit::updateOrCreate(
                ['email' => $email, 'data_date' => $dataDate],
                [
                    'name' => $officerNames[$email] ?? null,
                    'daily_reject' => $dailyReject,
                    'daily_approve' => $dailyApprove,
                    'daily_combined' => $dailyCombined,
                    'total_reject' => $totalReject,
                    'total_approve' => $totalApprove,
                    'pcl_count' => $pclCount,
                    'target_met' => $targetMet,
                ]
            );

            $count++;
        }

        fclose($handle);

        return $count;
    }
}
