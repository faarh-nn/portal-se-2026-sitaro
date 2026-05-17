<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

#[Signature('import:usaha {file}')]
#[Description('Import data usaha dari file xlsx')]
class ImportUsaha extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return 1;
        }

        $this->info('Membaca file XLSX...');
        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        // Skip header (baris pertama)
        $header = array_shift($rows);

        $this->info('Mulai import ' . count($rows) . ' data...');
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        // Chunk insert agar tidak timeout
        $chunks = array_chunk($rows, 500);

        foreach ($chunks as $chunk) {
            $data = [];
            foreach ($chunk as $row) {
                if (empty($row[0])) continue; // skip baris kosong
                $data[] = [
                    'nama_usaha'        => $row[0],
                    'alamat'            => $row[1],
                    'nama_provinsi'     => $row[2],
                    'nama_kabupaten'    => $row[3],
                    'nama_kecamatan'    => $row[4],
                    'nama_desa'         => $row[5],
                    'status_perusahaan' => $row[6],
                    'skala_usaha'       => $row[7],
                    'latitude'          => $row[8],
                    'longitude'         => $row[9],
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
            DB::table('usaha')->insert($data); // bulk insert per 500 baris
            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import selesai!');
        return 0;
    }
}
