<?php

namespace App\Console\Commands;

use App\Models\OfficerMapping;
use App\Models\PclDailySubmit;
use App\Models\PclPml;
use App\Models\PmlDailySubmit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDailySubmitsFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'import:daily-submits
                            {--pcl= : Path to PCL daily submits CSV file}
                            {--pml= : Path to PML daily submits CSV file}
                            {--date= : Data date (YYYY-MM-DD), defaults to today}';

    /**
     * The console command description.
     */
    protected $description = 'Import daily submits data from CSV files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dataDate = $this->option('date') ?? now()->format('Y-m-d');
        $pclPath = $this->option('pcl');
        $pmlPath = $this->option('pml');

        if (! $pclPath && ! $pmlPath) {
            $this->error('Please specify at least one CSV file with --pcl or --pml option.');

            return Command::FAILURE;
        }

        DB::beginTransaction();

        try {
            if ($pclPath) {
                $this->importPclDailySubmits($pclPath, $dataDate);
            }

            if ($pmlPath) {
                $this->importPmlDailySubmits($pmlPath, $dataDate);
            }

            DB::commit();

            $this->info("Successfully imported daily submits for date: {$dataDate}");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Import PCL daily submits from CSV.
     */
    private function importPclDailySubmits(string $filePath, string $dataDate): void
    {
        if (! file_exists($filePath)) {
            throw new \Exception("PCL CSV file not found: {$filePath}");
        }

        $this->info("Importing PCL daily submits from: {$filePath}");

        // Get officer names for lookup
        $officerNames = OfficerMapping::where('type', 'PCL')
            ->pluck('name', 'email')
            ->toArray();

        // Get kecamatans per PCL from existing data
        $kecamatansByEmail = PclDailySubmit::select('email', 'kecamatan')
            ->where('data_date', '<', $dataDate)
            ->get()
            ->groupBy('email')
            ->map(fn ($items) => $items->pluck('kecamatan')->filter()->unique()->values()->toArray());

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $email = strtolower(trim($row[0] ?? ''));
            if (empty($email)) {
                continue;
            }

            $dailySubmit = (int) ($row[3] ?? 0);
            $totalSubmit = (int) ($row[4] ?? $dailySubmit);

            // Get kecamatans from historical data or use existing
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

        $this->info("Imported {$count} PCL records.");
    }

    /**
     * Import PML daily submits from CSV.
     */
    private function importPmlDailySubmits(string $filePath, string $dataDate): void
    {
        if (! file_exists($filePath)) {
            throw new \Exception("PML CSV file not found: {$filePath}");
        }

        $this->info("Importing PML daily submits from: {$filePath}");

        // Get officer names for lookup
        $officerNames = OfficerMapping::where('type', 'PML')
            ->pluck('name', 'email')
            ->toArray();

        // Get PCL count per PML from pcl_pml mapping
        $pclCounts = PclPml::select('pml_email', DB::raw('COUNT(*) as count'))
            ->groupBy('pml_email')
            ->pluck('count', 'pml_email')
            ->toArray();

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

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

        $this->info("Imported {$count} PML records.");
    }
}
