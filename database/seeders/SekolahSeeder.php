<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('seeders/data/data_sekolah.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: $csvFile");
            return;
        }

        $file = fopen($csvFile, 'r');

        // Lewati header
        fgetcsv($file, 0, ';');

        while (($row = fgetcsv($file, 0, ';')) !== false) {

            // convert semua kolom ke UTF8
            $row = array_map(function ($value) {
                return $value ? mb_convert_encoding($value, 'UTF-8', 'auto') : null;
            }, $row);

            $row = array_pad($row, 21, null);

            DB::table('tbl_sekolah')->insert([
                'nama_sekolah' => trim($row[0]),
                'npsn' => trim($row[1]) ?: null,
                'nss' => trim($row[2]) ?: null,
                'npwp' => trim($row[3]) ?: null,
                'alamat' => trim($row[4]) ?: null,
                'desa' => trim($row[5]) ?: null,
                'kecamatan' => trim($row[6]) ?: null,
                'kabupaten' => trim($row[7]) ?: null,
                'provinsi' => trim($row[8]) ?: null,
                'tahun_berdiri' => trim($row[9]) ?: null,
                'nomor_sk_pendirian' => trim($row[10]) ?: null,
                'tgl_sk_pendirian' => !empty($row[11]) ? Carbon::parse($row[11]) : null,
                'status_sekolah' => trim($row[12]) ?: null,
                'nama_penyelenggara_yayasan' => trim($row[13]) ?: null,
                'alamat_penyelenggara_yayasan' => trim($row[14]) ?: null,
                'sk_pendirian_yayasan' => trim($row[15]) ?: null,
                'gedung_sekolah' => trim($row[16]) ?: null,
                'akreditasi_sekolah' => trim($row[17]) ?: null,
                'status_tanah_sekolah' => trim($row[18]) ?: null,
                'luas_tanah_sekolah' => trim($row[19]) ?: null,
                'email_sekolah' => trim($row[20]) ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
