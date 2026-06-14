<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KecamatanMapping;
use App\Models\KecamatanProgress;
use App\Models\MonitoringImport;
use App\Models\OfficerMapping;
use App\Models\PclProgress;
use App\Models\PmlProgress;
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
            }

            // Generate kecamatan progress from PCL data
            $this->generateKecamatanProgress($dataDate);

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

        // Aggregate by email (sum status columns, take one value for total_assignment)
        foreach (array_slice($rows, 1) as $row) {
            if (empty($row[0])) {
                continue;
            }

            $email = strtolower(trim($row[0]));

            if (! isset($aggregatedData[$email])) {
                $aggregatedData[$email] = [
                    'email' => $email,
                    'total_assignment' => (int) ($row[1] ?? 0),
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

        // Get PML emails for each block (for now, we'll need to determine PML assignment)
        // This would ideally come from the Excel data or another mapping table

        $count = 0;
        $aggregatedData = [];

        // Aggregate by email (take one value for total_assignment, sum status columns)
        foreach (array_slice($rows, 1) as $row) {
            if (empty($row[0])) {
                continue;
            }

            $email = strtolower(trim($row[0]));
            $blockId = (string) ($row[2] ?? '');
            $kode7 = substr($blockId, 0, 7);

            // Find matching kecamatan from mapping (take first match only)
            if (! isset($aggregatedData[$email])) {
                $kecamatan = null;
                foreach ($kecamatanMappings as $mapKode => $mapKecamatan) {
                    if (str_starts_with($kode7, $mapKode)) {
                        $kecamatan = $mapKecamatan;
                        break;
                    }
                }

                $aggregatedData[$email] = [
                    'email' => $email,
                    'name' => $officerNames[$email] ?? null,
                    'kecamatan' => $kecamatan,
                    'pml_email' => null,
                    'total_assignment' => (int) ($row[1] ?? 0),
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

        // Save aggregated data
        foreach ($aggregatedData as $email => $data) {
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
     * Generate kecamatan progress from PCL data.
     */
    private function generateKecamatanProgress(string $dataDate): void
    {
        // Get or create import record for kecamatan progress
        $import = MonitoringImport::where('type', 'data_pcl')
            ->where('status', 'completed')
            ->orderByDesc('imported_at')
            ->first();

        if (! $import) {
            return;
        }

        // Delete old kecamatan progress for this date
        KecamatanProgress::where('data_date', $dataDate)->delete();

        // Aggregate PCL data by kecamatan
        $kecamatanData = PclProgress::where('data_date', $dataDate)
            ->selectRaw('kecamatan, SUM(total_assignment) as total_assignment, SUM(open) as open, SUM(draft) as draft, SUM(submit) as submit, SUM(approve) as approve, SUM(reject) as reject, SUM(completed) as completed')
            ->groupBy('kecamatan')
            ->get();

        foreach ($kecamatanData as $data) {
            if (empty($data->kecamatan)) {
                continue;
            }

            // Get kode from kecamatan mapping
            $kode = KecamatanMapping::where('kecamatan', $data->kecamatan)->value('kode');

            KecamatanProgress::create([
                'kecamatan' => $data->kecamatan,
                'kode' => $kode,
                'total_assignment' => $data->total_assignment,
                'open' => $data->open,
                'draft' => $data->draft,
                'submit' => $data->submit,
                'approve' => $data->approve,
                'reject' => $data->reject,
                'completed' => $data->completed,
                'import_id' => $import->id,
                'data_date' => $dataDate,
            ]);
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

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
    }
}
