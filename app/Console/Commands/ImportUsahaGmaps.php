<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Signature('import:usaha-gmaps {file}')]
#[Description('Import data usaha hasil scraping Google Maps dari file XLSX')]
class ImportUsahaGmaps extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('file');

        if (! file_exists($path)) {
            $this->error("File tidak ditemukan: $path");

            return 1;
        }

        $this->info('Membaca file XLSX...');
        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        array_shift($rows); // skip header

        $rows = array_filter($rows, fn ($row) => ! empty($row[0])); // skip baris kosong

        $this->info('Mulai import '.count($rows).' data...');
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach (array_chunk($rows, 500) as $chunk) {
            $data = array_map(fn ($row) => [
                'nama_usaha' => $row[0],
                'kategori' => $row[1] ?? null,
                'alamat' => $row[2] ?? null,
                'nomor_telepon' => $row[3] ?? null,
                'website' => $row[4] ?? null,
                'jam_operasional' => $row[5] ?? null,
                'latitude' => $row[6] ?? null,
                'longitude' => $row[7] ?? null,
                'is_in_sbr' => strtoupper($row[8] ?? 'FALSE') === 'TRUE' ? true : false,
                'created_at' => now(),
                'updated_at' => now(),
            ], $chunk);

            DB::table('usaha_gmaps')->insert($data);
            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import selesai!');

        return 0;
    }
}
